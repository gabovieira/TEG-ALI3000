<?php
namespace App\Services;

use App\Models\Pago;
use App\Models\PagoDetalle;
use App\Models\User;
use App\Models\Empresa;
use App\Models\TarifaConsultor;
use App\Models\ConfiguracionFiscal;
use App\Models\DatosBancarios;
use App\Models\HistorialPago;
use App\Models\ComprobantePago;
use App\Models\TasaBCV;
use App\Notifications\PagoProcesadoNotification;
use App\Notifications\PagoConfirmadoNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class PagoService
{
    private $tasaBcvService;
    private $configuracionFiscal;
    private $pdfService;

    public function __construct(
        TasaBcvService $tasaBcvService, 
        ConfiguracionFiscal $configuracionFiscal,
        PDFService $pdfService
    ) {
        $this->tasaBcvService = $tasaBcvService;
        $this->configuracionFiscal = $configuracionFiscal;
        $this->pdfService = $pdfService;
    }

    /**
     * Req 1: Obtiene los períodos disponibles para pago
     */
    public function obtenerPeriodosDisponibles($tipo = 'quincenal', $meses = 6): array
    {
        $periodos = [];
        $fecha = Carbon::now();
        
        if ($tipo === 'quincenal') {
            for ($i = 0; $i < $meses; $i++) {
                $periodos[] = [
                    'id' => $fecha->format('Y-m').'-Q2',
                    'nombre' => 'Segunda Quincena '.$fecha->format('F Y'),
                    'inicio' => $fecha->copy()->startOfMonth()->addDays(15)->format('Y-m-d'),
                    'fin' => $fecha->copy()->endOfMonth()->format('Y-m-d')
                ];
                $periodos[] = [
                    'id' => $fecha->format('Y-m').'-Q1',
                    'nombre' => 'Primera Quincena '.$fecha->format('F Y'),
                    'inicio' => $fecha->copy()->startOfMonth()->format('Y-m-d'),
                    'fin' => $fecha->copy()->startOfMonth()->addDays(14)->format('Y-m-d')
                ];
                $fecha->subMonth();
            }
        } else {
            // Período personalizado - últimos 6 meses
            for ($i = 0; $i < $meses; $i++) {
                $periodos[] = [
                    'id' => $fecha->format('Y-m'),
                    'nombre' => $fecha->format('F Y'),
                    'inicio' => $fecha->copy()->startOfMonth()->format('Y-m-d'),
                    'fin' => $fecha->copy()->endOfMonth()->format('Y-m-d')
                ];
                $fecha->subMonth();
            }
        }
        
        return array_reverse($periodos);
    }

    /**
     * Req 1: Obtiene los consultores con horas pendientes de pago
     */
    public function obtenerConsultoresConHorasPendientes($periodo, $empresaId = null)
    {
        return User::where('tipo_usuario', 'consultor')
            ->whereHas('horasAprobadas', function($q) use ($periodo, $empresaId) {
                $q->whereBetween('fecha', [$periodo['inicio'], $periodo['fin']])
                    ->where('pagada', false);
                if ($empresaId) {
                    $q->where('empresa_id', $empresaId);
                }
            })

            ->with(['datosBancarios' => function($q) {
                $q->where('activo', true);
            }])
            ->get()
            ->map(function($consultor) use ($periodo, $empresaId) {
                $horasQuery = $consultor->horasAprobadas()
                    ->whereBetween('fecha', [$periodo['inicio'], $periodo['fin']])
                    ->where('pagada', false);
                
                if ($empresaId) {
                    $horasQuery->where('empresa_id', $empresaId);
                }

                $horas = $horasQuery->get();

                return [
                    'id' => $consultor->id,
                    'nombre' => $consultor->nombre_completo,
                    'horas' => $horas->sum('horas'),
                    'tiene_datos_bancarios' => $consultor->datosBancarios->isNotEmpty(),
                    'empresas' => $horas->groupBy('empresa_id')
                        ->map(function($grupo) {
                            $empresa = $grupo->first()->empresa;
                            return [
                                'id' => $empresa->id,
                                'nombre' => $empresa->nombre,
                                'horas' => $grupo->sum('horas')
                            ];
                        })->values()
                ];
            });
    }

    /**
     * Req 2: Calcula el pago para un consultor específico
     */
    public function calcularPago(User $consultor, array $periodo, array $empresasIds = null)
    {
        // Verificar datos bancarios
        if (!$consultor->datosBancarios()->where('activo', true)->exists()) {
            throw new \Exception('El consultor no tiene datos bancarios registrados');
        }

        $tasaBcv = $this->tasaBcvService->getTasaActual();
        
        $horasQuery = $consultor->horasAprobadas()
            ->whereBetween('fecha', [$periodo['inicio'], $periodo['fin']])
            ->where('pagada', false);
            
        if ($empresasIds) {
            $horasQuery->whereIn('empresa_id', $empresasIds);
        }
        
        $horasAprobadas = $horasQuery->get();
        
        if ($horasAprobadas->isEmpty()) {
            throw new \Exception('No hay horas aprobadas pendientes de pago para el período seleccionado');
        }

        try {
            DB::beginTransaction();

            $pago = new Pago([
                'usuario_id' => $consultor->id,
                'periodo_inicio' => $periodo['inicio'],
                'periodo_fin' => $periodo['fin'],
                'total_horas' => $horasAprobadas->sum('horas'),
                'iva_porcentaje' => $this->configuracionFiscal->iva_porcentaje,
                'islr_porcentaje' => $this->configuracionFiscal->islr_porcentaje,
                'tasa_cambio' => $tasaBcv->valor,
                'fecha_tasa_bcv' => $tasaBcv->fecha,
                'datos_bancarios_id' => $consultor->datosBancarios()->where('activo', true)->first()->id,
                'estado' => 'pendiente'
            ]);

            $detallesPorEmpresa = $horasAprobadas->groupBy('empresa_id')
                ->map(function($horas) use ($consultor) {
                    $empresa = $horas->first()->empresa;
                    $tarifa = TarifaConsultor::where('consultor_id', $consultor->id)
                        ->where('empresa_id', $empresa->id)
                        ->first();

                    if (!$tarifa) {
                        throw new \Exception("No hay tarifa configurada para el consultor en la empresa {$empresa->nombre}");
                    }
                        
                    return new PagoDetalle([
                        'empresa_id' => $empresa->id,
                        'horas' => $horas->sum('horas'),
                        'tarifa_por_hora' => $tarifa->tarifa_usd,
                        'tipo_moneda' => 'USD'
                    ]);
                });

            $totalIngresos = 0;
            foreach ($detallesPorEmpresa as $detalle) {
                $detalle->subtotal = $detalle->horas * $detalle->tarifa_por_hora;
                $detalle->iva = $detalle->subtotal * ($this->configuracionFiscal->iva_porcentaje / 100);
                $detalle->total_con_iva = $detalle->subtotal + $detalle->iva;
                $detalle->islr = $detalle->subtotal * ($this->configuracionFiscal->islr_porcentaje / 100);
                $detalle->total_neto = $detalle->total_con_iva - $detalle->islr;
                $totalIngresos += $detalle->subtotal;
            }

            $pago->monto_base_divisas = $totalIngresos;
            $pago->iva_divisas = $totalIngresos * ($this->configuracionFiscal->iva_porcentaje / 100);
            $pago->total_con_iva_divisas = $pago->monto_base_divisas + $pago->iva_divisas;
            $pago->islr_divisas = $totalIngresos * ($this->configuracionFiscal->islr_porcentaje / 100);
            $pago->total_menos_islr_divisas = $pago->total_con_iva_divisas - $pago->islr_divisas;
            
            // Conversión a bolívares
            $pago->monto_base_bs = $pago->monto_base_divisas * $tasaBcv->valor;
            $pago->iva_bs = $pago->iva_divisas * $tasaBcv->valor;
            $pago->total_con_iva_bs = $pago->total_con_iva_divisas * $tasaBcv->valor;
            $pago->islr_bs = $pago->islr_divisas * $tasaBcv->valor;
            $pago->total_menos_islr_bs = $pago->total_menos_islr_divisas * $tasaBcv->valor;

            $pago->save();
            $pago->detalles()->saveMany($detallesPorEmpresa);

            // Registrar en el historial
            $pago->historial()->create([
                'estado' => 'pendiente',
                'descripcion' => 'Pago generado',
                'usuario_id' => auth()->id()
            ]);

            DB::commit();

            return $pago->load(['detalles.empresa', 'consultor', 'datosBancarios']);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Req 4: Procesa un pago y genera el comprobante
     */
    public function procesarPago(Pago $pago, array $datos)
    {
        if (!in_array($pago->estado, ['pendiente', 'rechazado'])) {
            throw new \Exception('Este pago no puede ser procesado en su estado actual');
        }

        try {
            DB::beginTransaction();

            // Actualizar pago
            $pago->referencia_bancaria = $datos['referencia_bancaria'];
            $pago->fecha_pago = $datos['fecha_pago'];
            $pago->observaciones = $datos['observaciones'] ?? null;
            $pago->estado = 'procesado';
            $pago->procesado_por = auth()->id();
            $pago->save();

            // Marcar horas como pagadas
            $pago->detalles->each(function($detalle) use ($pago) {
                $horas = $pago->consultor->horasAprobadas()
                    ->whereBetween('fecha', [$pago->periodo_inicio, $pago->periodo_fin])
                    ->where('empresa_id', $detalle->empresa_id)
                    ->where('pagada', false)
                    ->get();

                $horas->each(function($hora) {
                    $hora->pagada = true;
                    $hora->save();
                });
            });

            // Generar comprobante
            $comprobante = $this->generarComprobantePago($pago);

            // Registrar en historial
            $pago->historial()->create([
                'estado' => 'procesado',
                'descripcion' => 'Pago procesado. Ref: ' . $datos['referencia_bancaria'],
                'usuario_id' => auth()->id()
            ]);

            // Notificar al consultor
            $pago->consultor->notify(new PagoProcesadoNotification($pago));

            DB::commit();

            return $comprobante;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Req 5: Confirma la recepción del pago por parte del consultor
     */
    public function confirmarPago(Pago $pago, array $datos)
    {
        if ($pago->estado !== 'procesado') {
            throw new \Exception('Solo se pueden confirmar pagos procesados');
        }

        try {
            DB::beginTransaction();

            $pago->estado = 'confirmado';
            $pago->fecha_confirmacion = now();
            $pago->comentarios_consultor = $datos['comentarios'] ?? null;
            $pago->save();

            // Actualizar comprobante
            $pago->comprobante->estado = 'confirmado';
            $pago->comprobante->save();

            // Registrar en historial
            $pago->historial()->create([
                'estado' => 'confirmado',
                'descripcion' => 'Pago confirmado por el consultor',
                'usuario_id' => $pago->consultor_id
            ]);

            // Notificar al administrador
            foreach (User::admins()->get() as $admin) {
                $admin->notify(new PagoConfirmadoNotification($pago));
            }

            DB::commit();
            return $pago;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Req 7: Genera el comprobante de pago en PDF
     */
    private function generarComprobantePago(Pago $pago): ComprobantePago
    {
        $pdf = $this->pdfService->generarComprobantePDF($pago);
        $filename = 'comprobante_' . $pago->id . '_' . now()->format('Ymd_His') . '.pdf';
        
        Storage::put('comprobantes/' . $filename, $pdf->output());

        return $pago->comprobante()->create([
            'archivo' => $filename,
            'estado' => 'generado',
            'hash' => hash_file('sha256', storage_path('app/comprobantes/' . $filename))
        ]);
    }

    /**
     * Anula un pago pendiente
     */
    public function anularPago(Pago $pago, string $motivo)
    {
        if ($pago->estado !== 'pendiente') {
            throw new \Exception('Solo se pueden anular pagos pendientes');
        }

        try {
            DB::beginTransaction();

            $pago->estado = 'anulado';
            $pago->observaciones = $motivo;
            $pago->save();

            $pago->historial()->create([
                'estado' => 'anulado',
                'descripcion' => 'Pago anulado: ' . $motivo,
                'usuario_id' => auth()->id()
            ]);

            DB::commit();
            return $pago;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
