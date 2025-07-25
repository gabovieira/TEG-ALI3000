<?php

namespace App\Services;

use App\Models\Pago;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\RegistroHoras;
use App\Models\Configuracion;
use App\Models\TasaBcv;
use App\Models\DatosBancario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagoService
{
    /**
     * Generar pagos para una quincena específica agrupados por consultor
     *
     * @param string $quincena Formato: YYYY-MM-Q (ej: 2025-07-1)
     * @param int|null $consultorId ID del consultor (opcional)
     * @param int $procesadoPor ID del usuario que realiza la acción
     * @return array Resultados de la generación de pagos
     */
    public function generarPagosQuincena($quincena, $consultorId = null, $procesadoPor)
    {
        // Validar formato de quincena
        if (!preg_match('/^\d{4}-\d{2}-[12]$/', $quincena)) {
            throw new \InvalidArgumentException("Formato de quincena inválido: {$quincena}");
        }
        
        // Obtener fechas de la quincena
        list($fechaInicio, $fechaFin) = $this->obtenerFechasQuincena($quincena);
        
        // Inicializar contadores para el resultado
        $resultados = [
            'total_generados' => 0,
            'total_consultores' => 0,
            'total_horas' => 0,
            'errores' => [],
            'pagos' => []
        ];
        
        try {
            DB::beginTransaction();
            
            // Obtener consultores con horas aprobadas en la quincena
            $consultores = $this->obtenerConsultoresConHorasAprobadas($fechaInicio, $fechaFin, $consultorId);
            $resultados['total_consultores'] = count($consultores);
            
            foreach ($consultores as $consultor) {
                try {
                    // Verificar si ya existe un pago para este consultor y quincena
                    if ($this->existePago($consultor->id, $quincena)) {
                        $resultados['errores'][] = "Ya existe un pago para el consultor {$consultor->primer_nombre} {$consultor->primer_apellido} para la quincena {$quincena}";
                        continue;
                    }
                    
                    // Obtener empresas y horas trabajadas por el consultor en la quincena
                    $detallesPago = $this->obtenerDetallesPagoConsultor($consultor->id, $fechaInicio, $fechaFin);
                    
                    if (empty($detallesPago['empresas'])) {
                        $resultados['errores'][] = "No hay horas aprobadas para el consultor {$consultor->primer_nombre} {$consultor->primer_apellido} en la quincena {$quincena}";
                        continue;
                    }
                    
                    // Calcular el pago total para el consultor (incluyendo todas las empresas)
                    $pago = $this->calcularPago($consultor->id, $quincena, $detallesPago, $procesadoPor);
                    
                    // Guardar el pago y sus detalles
                    DB::transaction(function() use ($pago, $detallesPago) {
                        $pago->save();
                        
                        // Guardar los detalles del pago por empresa
                        foreach ($detallesPago['empresas'] as $detalle) {
                            $pago->detalles()->create([
                                'empresa_id' => $detalle['empresa_id'],
                                'horas' => $detalle['horas'],
                                'tarifa_por_hora' => $detalle['tarifa'],
                                'monto_empresa_divisas' => $detalle['subtotal'], // Campo original de la tabla
                                'subtotal' => $detalle['subtotal'], // Campo nuevo para compatibilidad
                                'tipo_moneda' => $detalle['tipo_moneda'],
                                'tasa_cambio' => $detalle['tasa_cambio'] ?? null,
                                'subtotal_bs' => $detalle['subtotal_bs'] ?? null,
                            ]);
                        }
                    });
                    
                    // Actualizar contadores
                    $resultados['total_generados']++;
                    $resultados['total_horas'] += $detallesPago['total_horas'];
                    $resultados['pagos'][] = [
                        'id' => $pago->id,
                        'consultor' => $consultor->primer_nombre . ' ' . $consultor->primer_apellido,
                        'horas' => $detallesPago['total_horas'],
                        'monto_total' => $pago->monto_total,
                        'monto_neto' => $pago->monto_neto,
                        'estado' => $pago->estado,
                        'empresas' => $detallesPago['empresas']
                    ];
                    
                } catch (\Exception $e) {
                    $resultados['errores'][] = "Error al procesar pago para {$consultor->primer_nombre} {$consultor->primer_apellido}: " . $e->getMessage();
                    Log::error("Error al generar pago: " . $e->getMessage(), [
                        'consultor_id' => $consultor->id,
                        'quincena' => $quincena,
                        'exception' => $e->getTraceAsString()
                    ]);
                }
            }
            
            DB::commit();
            return $resultados;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al generar pagos de quincena: " . $e->getMessage(), [
                'quincena' => $quincena,
                'consultor_id' => $consultorId,
                'exception' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Calcular el pago total para un consultor en una quincena
     *
     * @param int $usuarioId ID del consultor
     * @param string $quincena Quincena en formato YYYY-MM-Q
     * @param array $detallesPago Arreglo con los detalles del pago por empresa
     * @param int $procesadoPor ID del usuario que realiza el cálculo
     * @return Pago
     */
    public function calcularPago($usuarioId, $quincena, $detallesPago, $procesadoPor)
    {
        // Obtener configuraciones fiscales
        $ivaPorcentaje = Configuracion::obtener('iva_porcentaje', 16.00);
        $islrPorcentaje = Configuracion::obtener('islr_porcentaje', 3.00);
        
        // Obtener tasa BCV más reciente
        $tasaBcv = TasaBcv::orderBy('fecha_registro', 'desc')->first();
        
        if (!$tasaBcv) {
            throw new \Exception("No se encontró tasa BCV para realizar el cálculo");
        }
        
        // Obtener datos bancarios del consultor
        $datosBancarios = DatosBancario::where('usuario_id', $usuarioId)
            ->where('es_principal', true)
            ->first();
        
        // Calcular montos totales
        $montoTotal = $detallesPago['monto_total'];
        $ivaMonto = $montoTotal * ($ivaPorcentaje / 100);
        $islrMonto = $montoTotal * ($islrPorcentaje / 100);
        $montoNeto = $montoTotal + $ivaMonto - $islrMonto;
        
        // Crear el objeto Pago usando los campos de la estructura original
        $pago = new Pago([
            'usuario_id' => $usuarioId,
            'empresa_id' => $detallesPago['empresas'][0]['empresa_id'], // Usar la primera empresa por ahora
            'quincena' => $quincena,
            'horas' => $detallesPago['total_horas'],
            'tarifa_por_hora' => $detallesPago['empresas'][0]['tarifa'],
            'monto_total' => $montoTotal,
            'iva_porcentaje' => $ivaPorcentaje,
            'iva_monto' => $ivaMonto,
            'islr_porcentaje' => $islrPorcentaje,
            'islr_monto' => $islrMonto,
            'monto_neto' => $montoNeto,
            'ingreso_divisas' => $montoTotal,
            'monto_base_divisas' => $montoTotal,
            'iva_divisas' => $ivaMonto,
            'total_con_iva_divisas' => $montoTotal + $ivaMonto,
            'islr_divisas' => $islrMonto,
            'total_menos_islr_divisas' => $montoNeto,
            'tasa_cambio' => $tasaBcv->tasa,
            'fecha_tasa_bcv' => $tasaBcv->fecha_registro,
            'monto_base_bs' => $montoTotal * $tasaBcv->tasa,
            'iva_bs' => $ivaMonto * $tasaBcv->tasa,
            'total_con_iva_bs' => ($montoTotal + $ivaMonto) * $tasaBcv->tasa,
            'islr_bs' => $islrMonto * $tasaBcv->tasa,
            'total_menos_islr_bs' => $montoNeto * $tasaBcv->tasa,
            'estado' => 'pendiente',
            'procesado_por' => $procesadoPor,
            'datos_bancarios_id' => $datosBancarios ? $datosBancarios->id : null
        ]);
        
        return $pago;
    }
    
    /**
     * Verificar si ya existe un pago para el consultor y quincena
     *
     * @param int $usuarioId ID del consultor
     * @param string $quincena Quincena en formato YYYY-MM-Q
     * @return bool
     */
    public function existePago($usuarioId, $quincena)
    {
        return Pago::where('usuario_id', $usuarioId)
                 ->where('quincena', $quincena)
                 ->where('estado', '!=', 'anulado')
                 ->exists();
    }
    
    /**
     * Obtener los detalles de pago para un consultor en un período
     *
     * @param int $consultorId ID del consultor
     * @param string $fechaInicio Fecha de inicio del período
     * @param string $fechaFin Fecha de fin del período
     * @return array Detalles del pago agrupados por empresa
     */
    public function obtenerDetallesPagoConsultor($consultorId, $fechaInicio, $fechaFin)
    {
        Log::info("Obteniendo detalles de pago", [
            'consultor_id' => $consultorId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);
        
        // Obtener registros de horas aprobadas
        $registros = RegistroHoras::where('usuario_id', $consultorId)
            ->where('estado', 'aprobado')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->with('empresa')
            ->get();
        
        Log::info("Registros encontrados: " . $registros->count());
        
        if ($registros->isEmpty()) {
            return [
                'total_horas' => 0,
                'monto_total' => 0,
                'empresas' => []
            ];
        }
        
        // Agrupar por empresa y calcular totales
        $empresas = [];
        $totalHoras = 0;
        $montoTotal = 0;
        
        // Obtener configuraciones fiscales para el cálculo
        $ivaPorcentaje = \App\Models\Configuracion::obtener('iva_porcentaje', 16.00);
        
        foreach ($registros->groupBy('empresa_id') as $empresaId => $registrosEmpresa) {
            $empresa = $registrosEmpresa->first()->empresa;
            $horasEmpresa = $registrosEmpresa->sum('horas_trabajadas');
            $tarifa = $this->obtenerTarifaConsultor($consultorId, $empresaId);
            
            // La tarifa incluye IVA, necesitamos calcular el monto base (sin IVA)
            $montoConIva = $horasEmpresa * $tarifa;
            $montoBase = $montoConIva / (1 + ($ivaPorcentaje / 100));
            $subtotal = $montoBase; // El subtotal es el monto base sin IVA
            
            // Determinar tipo de moneda (asumimos que es el mismo para todas las tarifas del consultor)
            $tipoMoneda = 'USD'; // Por defecto, se puede obtener de la configuración
            $tasaCambio = null;
            $subtotalBs = null;
            
            if ($tipoMoneda === 'USD') {
                $tasaBcv = TasaBcv::orderBy('fecha_registro', 'desc')->first();
                if ($tasaBcv) {
                    $tasaCambio = $tasaBcv->tasa;
                    $subtotalBs = $subtotal * $tasaCambio;
                }
            } else {
                $subtotalBs = $subtotal;
            }
            
            $empresas[] = [
                'empresa_id' => $empresaId,
                'empresa_nombre' => $empresa->nombre,
                'empresa_rif' => $empresa->rif,
                'horas' => $horasEmpresa,
                'tarifa' => $tarifa,
                'monto_con_iva' => $montoConIva,
                'monto_base' => $montoBase,
                'subtotal' => $subtotal, // Este es el monto base para los cálculos
                'tipo_moneda' => $tipoMoneda,
                'tasa_cambio' => $tasaCambio,
                'subtotal_bs' => $subtotalBs
            ];
            
            $totalHoras += $horasEmpresa;
            $montoTotal += $subtotal;
        }
        
        return [
            'total_horas' => $totalHoras,
            'monto_total' => $montoTotal,
            'empresas' => $empresas
        ];
    }
    
    /**
     * Obtener las fechas de inicio y fin de una quincena
     */
    public function obtenerFechasQuincena($quincena)
    {
        // Formato esperado: "YYYY-MM-Q" (ej: "2025-07-1" para primera quincena de julio 2025)
        $partes = explode('-', $quincena);
        
        if (count($partes) != 3) {
            throw new \Exception("Formato de quincena inválido: {$quincena}");
        }
        
        $anio = (int)$partes[0];
        $mes = (int)$partes[1];
        $quincena = (int)$partes[2];
        
        if ($quincena == 1) {
            // Primera quincena: del 1 al 15
            $fechaInicio = Carbon::create($anio, $mes, 1)->startOfDay();
            $fechaFin = Carbon::create($anio, $mes, 15)->endOfDay();
        } else {
            // Segunda quincena: del 16 al último día del mes
            $fechaInicio = Carbon::create($anio, $mes, 16)->startOfDay();
            $fechaFin = Carbon::create($anio, $mes)->endOfMonth()->endOfDay();
        }
        
        return [$fechaInicio, $fechaFin];
    }
    
    /**
     * Obtener consultores que tienen horas aprobadas en la quincena
     */
    public function obtenerConsultoresConHorasAprobadas($fechaInicio, $fechaFin, $consultorId = null)
    {
        $query = Usuario::whereHas('registrosHoras', function ($query) use ($fechaInicio, $fechaFin) {
            $query->where('estado', 'aprobado')
                  ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        })
        ->where('tipo_usuario', 'consultor')
        ->where('estado', 'activo');
        
        // Si se especifica un consultor específico, filtrar por él
        if ($consultorId) {
            $query->where('id', $consultorId);
        }
        
        return $query->get();
    }
    
    /**
     * Obtener empresas para las que trabajó un consultor en la quincena
     */
    public function obtenerEmpresasDelConsultor($consultorId, $fechaInicio, $fechaFin, $empresaId = null)
    {
        $query = Empresa::whereHas('registrosHoras', function ($query) use ($consultorId, $fechaInicio, $fechaFin) {
            $query->where('usuario_id', $consultorId)
                  ->where('estado', 'aprobado')
                  ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        })
        ->where('estado', 'activa');
        
        if ($empresaId) {
            $query->where('id', $empresaId);
        }
        
        return $query->get();
    }
    
    /**
     * Obtener horas aprobadas para un consultor y empresa en un período
     */
    public function obtenerHorasAprobadas($consultorId, $empresaId, $fechaInicio, $fechaFin)
    {
        return RegistroHoras::where('usuario_id', $consultorId)
                          ->where('empresa_id', $empresaId)
                          ->where('estado', 'aprobado')
                          ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                          ->sum('horas_trabajadas');
    }
    
    /**
     * Obtener tarifa del consultor para una empresa
     * En un sistema real, esto vendría de una tabla de tarifas
     * Por ahora, usamos un valor fijo o configuración
     */
    public function obtenerTarifaConsultor($consultorId, $empresaId)
    {
        // Obtener la tarifa desde datos_laborales del consultor
        $datosLaborales = \App\Models\DatosLaborales::where('usuario_id', $consultorId)->first();
        
        if ($datosLaborales && $datosLaborales->tarifa_por_hora > 0) {
            return $datosLaborales->tarifa_por_hora;
        }
        
        // Si no hay tarifa configurada, lanzar excepción
        throw new \Exception("No hay tarifa configurada para el consultor ID: {$consultorId}. La tarifa debe estar configurada en sus datos laborales.");
    }
    
    /**
     * Marcar un pago como pagado
     */
    public function marcarComoPagado($pagoId, $fechaPago, $observaciones = null, $referenciaBancaria = null)
    {
        $pago = Pago::findOrFail($pagoId);
        
        if ($pago->estado !== 'pendiente') {
            throw new \Exception("Solo se pueden marcar como pagados los pagos en estado pendiente");
        }
        
        $pago->estado = 'pagado';
        $pago->fecha_pago = $fechaPago;
        
        if ($observaciones) {
            $pago->observaciones = $observaciones;
        }
        
        if ($referenciaBancaria) {
            $pago->referencia_bancaria = $referenciaBancaria;
        }
        
        // Generar comprobante de pago
        try {
            $nombreComprobante = $this->generarComprobante($pago);
            $pago->comprobante_pago = $nombreComprobante;
        } catch (\Exception $e) {
            Log::warning("No se pudo generar el comprobante automáticamente: " . $e->getMessage(), [
                'pago_id' => $pago->id
            ]);
        }
        
        $pago->save();
        
        // Notificar al consultor que el pago fue realizado
        $this->notificarConsultorPagoRealizado($pago);
        
        return $pago;
    }
    
    /**
     * Notificar al consultor que el pago fue realizado
     */
    private function notificarConsultorPagoRealizado($pago)
    {
        // Cargar relaciones necesarias si no están cargadas
        if (!$pago->relationLoaded('consultor')) {
            $pago->load('consultor');
        }
        
        // Aquí puedes implementar la notificación (email, notificación en la app, etc.)
        // Por ahora solo registramos en el log
        Log::info("Notificación de pago realizado enviada", [
            'pago_id' => $pago->id,
            'consultor_id' => $pago->usuario_id,
            'monto' => $pago->monto_neto,
            'fecha_pago' => $pago->fecha_pago
        ]);
    }
    
    /**
     * Procesar un pago individual
     *
     * @param int $pagoId ID del pago
     * @param int $procesadoPorId ID del usuario que procesa el pago
     * @param int|null $datosBancariosId ID de los datos bancarios utilizados (opcional)
     * @param string|null $observaciones Observaciones opcionales
     * @return Pago
     */
    public function procesarPago($pagoId, $procesadoPorId, $datosBancariosId = null, $observaciones = null)
    {
        $pago = Pago::findOrFail($pagoId);
        
        if ($pago->estado !== 'pendiente') {
            throw new \Exception("Solo se pueden procesar pagos en estado pendiente");
        }
        
        DB::beginTransaction();
        
        try {
            // Actualizar datos del pago
            $pago->procesado_por = $procesadoPorId;
            $pago->fecha_procesado = now();
            $pago->estado = 'procesado';
            $pago->fecha_pago = now();
            
            if ($datosBancariosId) {
                $pago->datos_bancarios_id = $datosBancariosId;
            }
            
            if ($observaciones) {
                $pago->observaciones = $observaciones;
            }
            
            // Generar comprobante
            $nombreComprobante = $this->generarComprobante($pago);
            $pago->comprobante_pago = $nombreComprobante;
            
            $pago->save();
            
            // Notificar al consultor
            $this->notificarConsultor($pago);
            
            DB::commit();
            return $pago;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al procesar pago: " . $e->getMessage(), [
                'pago_id' => $pagoId,
                'exception' => $e
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Anular un pago
     */
    public function anularPago($pagoId, $motivo)
    {
        $pago = Pago::findOrFail($pagoId);
        
        if ($pago->estado === 'pagado') {
            throw new \Exception("No se pueden anular pagos que ya han sido pagados");
        }
        
        $pago->estado = 'anulado';
        $pago->observaciones = $motivo;
        $pago->save();
        
        return $pago;
    }
    
    /**
     * Generar comprobante de pago en PDF
     *
     * @param Pago $pago Objeto de pago
     * @return string Nombre del archivo generado
     */
    public function generarComprobante($pago)
    {
        // Cargar datos necesarios para el comprobante
        $pago->load(['consultor', 'detalles.empresa', 'procesador', 'datosBancarios']);
        
        // Generar nombre de archivo
        $nombreArchivo = $pago->generarNombreComprobante();
        
        // Crear directorio si no existe
        $directorio = storage_path('app/public/comprobantes');
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }
        
        // Generar PDF usando DOMPDF sin dependencias de GD
        try {
            $pdf = \PDF::loadView('pdf.comprobante-pago', compact('pago'));
            $pdf->setPaper('letter', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
                'isRemoteEnabled' => false,
                'defaultFont' => 'Arial',
                'dpi' => 96,
                'defaultPaperSize' => 'letter'
            ]);
            $pdf->save($directorio . '/' . $nombreArchivo);
        } catch (\Exception $e) {
            \Log::error("Error generando PDF: " . $e->getMessage());
            throw new \Exception("Error al generar el comprobante: " . $e->getMessage());
        }
        
        return $nombreArchivo;
    }
    
    /**
     * Obtener la URL pública del comprobante de pago
     *
     * @param string $nombreArchivo Nombre del archivo del comprobante
     * @return string URL pública del comprobante
     */
    public function obtenerUrlComprobante($nombreArchivo)
    {
        return asset('storage/comprobantes/' . $nombreArchivo);
    }
    
    /**
     * Descargar comprobante de pago
     *
     * @param int $pagoId ID del pago
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function descargarComprobante($pagoId)
    {
        $pago = Pago::with(['consultor', 'detalles.empresa', 'procesador', 'datosBancarios'])->findOrFail($pagoId);
        
        // SIEMPRE regenerar el comprobante para usar el diseño más reciente
        $nombreArchivo = $this->generarComprobante($pago);
        
        // Actualizamos el registro con el nombre del comprobante
        $pago->comprobante_pago = $nombreArchivo;
        $pago->save();
        
        $rutaArchivo = storage_path('app/public/comprobantes/' . $nombreArchivo);
        
        return response()->download($rutaArchivo);
    }
    
    /**
     * Notificar al consultor sobre el pago procesado
     *
     * @param Pago $pago Objeto de pago
     * @return void
     */
    public function notificarConsultor($pago)
    {
        // Cargar relaciones necesarias si no están cargadas
        if (!$pago->relationLoaded('consultor')) {
            $pago->load('consultor');
        }
        
        // Enviar notificación al consultor
        $pago->consultor->notify(new \App\Notifications\PagoProcesadoNotification($pago));
        
        // Registrar en el log
        Log::info("Notificación de pago procesado enviada", [
            'pago_id' => $pago->id,
            'consultor_id' => $pago->usuario_id,
            'monto' => $pago->total_menos_islr_divisas
        ]);
    }
    
    /**
     * Obtener datos bancarios de un consultor
     *
     * @param int $consultorId ID del consultor
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerDatosBancarios($consultorId)
    {
        return \App\Models\DatosBancario::where('usuario_id', $consultorId)
                                      ->orderBy('es_principal', 'desc')
                                      ->get();
    }
    
    /**
     * Obtener pagos pendientes de confirmación para un consultor
     *
     * @param int $consultorId ID del consultor
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerPagosPendientesConfirmacion($consultorId)
    {
        return Pago::where('usuario_id', $consultorId)
                 ->where('estado', 'procesado')
                 ->whereNotNull('fecha_procesado')
                 ->whereNull('fecha_confirmacion')
                 ->orderBy('fecha_procesado', 'desc')
                 ->get();
    }
    
    /**
     * Confirmar recepción de pago por parte del consultor
     *
     * @param int $pagoId ID del pago
     * @param string|null $comentario Comentario opcional del consultor
     * @return Pago
     */
    public function confirmarRecepcionPago($pagoId, $comentario = null)
    {
        $pago = Pago::findOrFail($pagoId);
        
        if ($pago->estado !== 'pagado') {
            throw new \Exception("Solo se pueden confirmar pagos en estado pagado");
        }
        
        $pago->confirmarRecepcion($comentario);
        
        // Cargar relaciones necesarias
        $pago->load(['consultor', 'procesador']);
        
        // Notificar al administrador que procesó el pago
        if ($pago->procesador) {
            $pago->procesador->notify(new \App\Notifications\PagoConfirmadoNotification($pago));
            
            // Registrar en el log
            Log::info("Notificación de pago confirmado enviada", [
                'pago_id' => $pago->id,
                'consultor_id' => $pago->usuario_id,
                'procesador_id' => $pago->procesado_por
            ]);
        }
        
        return $pago;
    }
}