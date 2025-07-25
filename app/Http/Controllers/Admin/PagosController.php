<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\TasaBcv;
use App\Services\PagoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagosController extends Controller
{
    protected $pagoService;
    
    public function __construct(PagoService $pagoService)
    {
        $this->pagoService = $pagoService;
        // Los middleware se definen en las rutas, no en el controlador
    }
    
    /**
     * Mostrar lista de pagos
     */
    public function index(Request $request)
    {
        // Filtros
        $estado = $request->estado;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $consultorId = $request->consultor_id;
        $quincena = $request->quincena;
        
        // Consulta base
        $query = Pago::with(['consultor', 'procesador', 'detalles.empresa'])
                    ->orderBy('fecha_creacion', 'desc');
        
        // Aplicar filtros
        $query->when($estado, function($q) use ($estado) {
            return $q->where('estado', $estado);
        })
        ->when($consultorId, function($q) use ($consultorId) {
            return $q->where('consultor_id', $consultorId);
        })
        ->when($quincena, function($q) use ($quincena) {
            return $q->where('periodo', $quincena);
        })
        ->when($fechaInicio && $fechaFin, function($q) use ($fechaInicio, $fechaFin) {
            return $q->whereBetween('fecha_pago', [$fechaInicio, $fechaFin]);
        });
        
        // Obtener resultados paginados
        $pagos = $query->paginate(15);
        
        // Obtener consultores para los filtros
        $consultores = Usuario::where('tipo_usuario', 'consultor')
                            ->where('estado', 'activo')
                            ->orderBy('primer_nombre')
                            ->get();
        
        // Obtener quincenas disponibles (últimos 6 meses)
        $quincenas = $this->obtenerQuincenasDisponibles();
        
        return view('admin.pagos.index', [
            'pagos' => $pagos,
            'consultores' => $consultores,
            'quincenas' => $quincenas,
            'filtros' => [
                'estado' => $estado,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'consultor_id' => $consultorId,
                'quincena' => $quincena
            ]
        ]);
    }
    
    /**
     * Mostrar formulario para generar pagos
     */
    public function mostrarGenerarPagos()
    {
        // Obtener quincenas disponibles (últimos 3 meses)
        $quincenas = $this->obtenerQuincenasDisponibles(3);
        
        // Obtener consultores activos
        $consultores = Usuario::where('tipo_usuario', 'consultor')
                            ->where('estado', 'activo')
                            ->orderBy('primer_nombre')
                            ->get();
        
        // Obtener empresas para el filtro
        $empresas = Empresa::where('estado', 'activa')
                         ->orderBy('nombre')
                         ->get();
        
        // Obtener tasa BCV actual
        $tasaBcv = TasaBcv::orderBy('fecha_registro', 'desc')->first();
        
        return view('admin.pagos.generar', [
            'quincenas' => $quincenas,
            'consultores' => $consultores,
            'empresas' => $empresas,
            'tasaBcv' => $tasaBcv
        ]);
    }
    
    /**
     * Generar pagos
     */
    public function generarPagos(Request $request)
    {
        // Validar campos básicos
        $request->validate([
            'quincena' => 'required|string',
            'consultor_id' => 'required|exists:usuarios,id'
        ]);

        try {
            // Generar pagos por quincena
            $resultados = $this->pagoService->generarPagosQuincena(
                $request->quincena,
                $request->consultor_id,
                Auth::id()
            );
            
            // Verificar si hubo errores
            if (count($resultados['errores']) > 0) {
                return redirect()->route('admin.pagos.index')
                               ->with('warning', "Se generaron {$resultados['total_generados']} pagos, pero hubo " . count($resultados['errores']) . " errores. Revise el log para más detalles.")
                               ->with('resultados', $resultados);
            }
            
            return redirect()->route('admin.pagos.index')
                           ->with('success', "Se generaron {$resultados['total_generados']} pagos correctamente para {$resultados['total_consultores']} consultores.")
                           ->with('resultados', $resultados);
            
        } catch (\Exception $e) {
            Log::error("Error al generar pagos: " . $e->getMessage(), [
                'quincena' => $request->quincena,
                'consultor_id' => $request->consultor_id,
                'exception' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                           ->with('error', "Error al generar pagos: " . $e->getMessage())
                           ->withInput();
        }
    }
    
    /**
     * Calcular honorarios para un consultor específico (AJAX)
     */
    public function calcular(Request $request)
    {
        $request->validate([
            'quincena' => 'required|string',
            'consultor_id' => 'required|exists:usuarios,id'
        ]);

        Log::info("Calculando pago para consultor: {$request->consultor_id}, quincena: {$request->quincena}");

        try {
            // Obtener fechas de la quincena
            list($fechaInicio, $fechaFin) = $this->pagoService->obtenerFechasQuincena($request->quincena);
            
            // Obtener detalles del pago para el consultor
            $detallesPago = $this->pagoService->obtenerDetallesPagoConsultor(
                $request->consultor_id,
                $fechaInicio,
                $fechaFin
            );
            
            Log::info("Detalles obtenidos", $detallesPago);
            
            if (empty($detallesPago['empresas']) || $detallesPago['total_horas'] == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'El consultor no tiene horas aprobadas para la quincena seleccionada.',
                    'debug' => [
                        'total_horas' => $detallesPago['total_horas'] ?? 0,
                        'empresas_count' => count($detallesPago['empresas'] ?? []),
                        'fecha_inicio' => $fechaInicio->format('Y-m-d'),
                        'fecha_fin' => $fechaFin->format('Y-m-d')
                    ]
                ]);
            }
            
            // Obtener configuraciones fiscales
            $ivaPorcentaje = \App\Models\Configuracion::obtener('iva_porcentaje', 16.00);
            $islrPorcentaje = \App\Models\Configuracion::obtener('islr_porcentaje', 3.00);
            
            // Obtener tasa BCV
            $tasaBcv = \App\Models\TasaBcv::orderBy('fecha_registro', 'desc')->first();
            
            if (!$tasaBcv) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay tasa BCV disponible para realizar el cálculo.'
                ]);
            }
            
            // Calcular totales
            $montoBase = $detallesPago['monto_total'];
            $ivaMonto = $montoBase * ($ivaPorcentaje / 100);
            $islrMonto = $montoBase * ($islrPorcentaje / 100);
            $totalPagar = $montoBase + $ivaMonto - $islrMonto;
            $totalPagarBs = $totalPagar * $tasaBcv->tasa;
            
            // Obtener datos bancarios del consultor
            $datosBancarios = \App\Models\DatosBancario::where('usuario_id', $request->consultor_id)
                                                     ->where('es_principal', true)
                                                     ->first();
            
            return response()->json([
                'success' => true,
                'calculo' => [
                    'total_horas' => $detallesPago['total_horas'],
                    'monto_base' => $montoBase,
                    'iva_porcentaje' => $ivaPorcentaje,
                    'iva_monto' => $ivaMonto,
                    'islr_porcentaje' => $islrPorcentaje,
                    'islr_monto' => $islrMonto,
                    'total_pagar' => $totalPagar,
                    'total_pagar_bs' => $totalPagarBs,
                    'tasa_bcv' => $tasaBcv->tasa,
                    'fecha_tasa_bcv' => $tasaBcv->fecha_registro->format('d/m/Y'),
                    'empresas' => $detallesPago['empresas']
                ],
                'datos_bancarios' => $datosBancarios ? [
                    'banco' => $datosBancarios->banco,
                    'tipo_cuenta' => $datosBancarios->tipo_cuenta,
                    'numero_cuenta' => $datosBancarios->numero_cuenta,
                    'titular' => $datosBancarios->titular,
                    'cedula_rif' => $datosBancarios->cedula_rif
                ] : null
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error al calcular honorarios: " . $e->getMessage(), [
                'quincena' => $request->quincena,
                'consultor_id' => $request->consultor_id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular honorarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener vista previa de consultores con horas aprobadas (AJAX)
     */
    public function preview(Request $request)
    {
        $request->validate([
            'quincena' => 'required|string',
            'consultor_id' => 'nullable|exists:usuarios,id'
        ]);

        try {
            // Obtener fechas de la quincena
            list($fechaInicio, $fechaFin) = $this->pagoService->obtenerFechasQuincena($request->quincena);
            
            // Obtener consultores con horas aprobadas
            $consultores = $this->pagoService->obtenerConsultoresConHorasAprobadas(
                $fechaInicio, 
                $fechaFin, 
                $request->consultor_id
            );
            
            $resultado = [];
            
            foreach ($consultores as $consultor) {
                // Obtener detalles del pago para este consultor
                $detalles = $this->pagoService->obtenerDetallesPagoConsultor(
                    $consultor->id,
                    $fechaInicio,
                    $fechaFin
                );
                
                $resultado[] = [
                    'id' => $consultor->id,
                    'nombre' => $consultor->primer_nombre . ' ' . $consultor->primer_apellido,
                    'total_horas' => $detalles['total_horas'],
                    'empresas' => collect($detalles['empresas'])->map(function($empresa) {
                        return [
                            'nombre' => $empresa['empresa_nombre'],
                            'horas' => $empresa['horas']
                        ];
                    })->toArray()
                ];
            }
            
            return response()->json([
                'success' => true,
                'consultores' => $resultado
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error al obtener vista previa: " . $e->getMessage(), [
                'quincena' => $request->quincena,
                'consultor_id' => $request->consultor_id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener vista previa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar detalle de un pago
     */
    public function show($id)
    {
        $pago = Pago::with([
            'consultor', 
            'consultor.datosLaborales', 
            'procesador',
            'detalles.empresa'
        ])->findOrFail($id);
        
        // Calcular totales para la vista
        $totales = [
            'horas' => $pago->detalles->sum('horas'),
            'subtotal' => $pago->detalles->sum('subtotal'),
            'iva' => $pago->detalles->sum('iva'),
            'total' => $pago->detalles->sum('total')
        ];
        
        return view('admin.pagos.show', [
            'pago' => $pago,
            'totales' => $totales
        ]);
    }
    
    /**
     * Marcar un pago como pagado
     */
    public function marcarPagado(Request $request, $id)
    {
        $request->validate([
            'fecha_pago' => 'required|date',
            'referencia_bancaria' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string|max:500'
        ]);
        
        try {
            $pago = $this->pagoService->marcarComoPagado(
                $id,
                $request->fecha_pago,
                $request->observaciones,
                $request->referencia_bancaria
            );
            
            return redirect()->back()
                           ->with('success', "El pago ha sido marcado como pagado correctamente. El consultor recibirá una notificación para confirmar la recepción.");
            
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', "Error al marcar el pago como pagado: " . $e->getMessage());
        }
    }
    
    /**
     * Anular un pago
     */
    public function anular(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);
        
        try {
            $pago = $this->pagoService->anularPago(
                $id,
                $request->motivo
            );
            
            return redirect()->back()
                           ->with('success', "El pago ha sido anulado correctamente.");
            
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', "Error al anular el pago: " . $e->getMessage());
        }
    }
    
    /**
     * Calcular pago individual (AJAX)
     */
    public function calcularPagoIndividual(Request $request)
    {
        $request->validate([
            'consultor_id' => 'required|exists:usuarios,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);
        
        try {
            // Obtener datos del consultor
            $consultor = Usuario::findOrFail($request->consultor_id);
            
            // Obtener fechas del período
            $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();
            
            // Obtener detalles del pago para el consultor en el período
            $detallesPago = $this->pagoService->obtenerDetallesPagoConsultor(
                $request->consultor_id,
                $fechaInicio,
                $fechaFin
            );
            
            if (empty($detallesPago['empresas'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay horas aprobadas para el consultor en el período seleccionado'
                ], 400);
            }
            
            // Obtener datos bancarios del consultor
            $datosBancarios = \App\Models\DatosBancario::where('usuario_id', $request->consultor_id)
                                                     ->where('es_principal', true)
                                                     ->first();
            
            // Obtener tasa BCV para el cálculo
            $tasaBcv = TasaBcv::orderBy('fecha_registro', 'desc')->first();
            
            // Preparar respuesta con los detalles del pago
            $response = [
                'success' => true,
                'consultor' => [
                    'id' => $consultor->id,
                    'nombre' => $consultor->primer_nombre . ' ' . $consultor->primer_apellido,
                    'email' => $consultor->email
                ],
                'periodo' => [
                    'fecha_inicio' => $fechaInicio->format('Y-m-d'),
                    'fecha_fin' => $fechaFin->format('Y-m-d')
                ],
                'detalles' => [],
                'totales' => [
                    'horas' => 0,
                    'subtotal' => 0,
                    'iva' => 0,
                    'total' => 0,
                    'tasa_cambio' => $tasaBcv ? $tasaBcv->tasa : 0,
                    'fecha_tasa_bcv' => $tasaBcv ? $tasaBcv->fecha_registro : null
                ],
                'datos_bancarios' => $datosBancarios ? [
                    'id' => $datosBancarios->id,
                    'banco' => $datosBancarios->banco,
                    'tipo_cuenta' => $datosBancarios->tipo_cuenta,
                    'numero_cuenta' => $datosBancarios->numero_cuenta,
                    'cedula_rif' => $datosBancarios->cedula_rif,
                    'titular' => $datosBancarios->titular
                ] : null
            ];
            
            // Procesar detalles por empresa
            foreach ($detallesPago['empresas'] as $detalle) {
                $response['detalles'][] = [
                    'empresa_id' => $detalle['empresa_id'],
                    'empresa_nombre' => $detalle['empresa_nombre'],
                    'horas' => $detalle['horas'],
                    'tarifa' => $detalle['tarifa'],
                    'subtotal' => $detalle['subtotal'],
                    'iva_porcentaje' => $detalle['iva_porcentaje'],
                    'iva' => $detalle['iva'],
                    'total' => $detalle['total'],
                    'tipo_moneda' => $detalle['tipo_moneda'],
                    'tasa_cambio' => $detalle['tasa_cambio']
                ];
                
                // Acumular totales
                $response['totales']['horas'] += $detalle['horas'];
                $response['totales']['subtotal'] += $detalle['subtotal'];
                $response['totales']['iva'] += $detalle['iva'];
                $response['totales']['total'] += $detalle['total'];
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error("Error al calcular pago individual: " . $e->getMessage(), [
                'consultor_id' => $request->consultor_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular pago: ' . $e->getMessage()
            ], 500);
        }
    }
    

    
    /**
     * Procesar un pago
     */
    public function procesarPago(Request $request, $id)
    {
        $request->validate([
            'datos_bancarios_id' => 'required|exists:datos_bancarios,id',
            'observaciones' => 'nullable|string|max:500'
        ]);
        
        try {
            // Procesar el pago
            $pago = $this->pagoService->procesarPago(
                $id,
                Auth::id(),
                $request->datos_bancarios_id,
                $request->observaciones
            );
            
            return redirect()->route('admin.pagos.show', $pago->id)
                           ->with('success', 'Pago procesado correctamente. Se ha notificado al consultor.');
            
        } catch (\Exception $e) {
            Log::error("Error al procesar pago: " . $e->getMessage(), [
                'pago_id' => $id,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al procesar pago: ' . $e->getMessage());
        }
    }
    
    /**
     * Descargar comprobante de pago
     */
    public function descargarComprobante($id)
    {
        try {
            return $this->pagoService->descargarComprobante($id);
        } catch (\Exception $e) {
            Log::error("Error al descargar comprobante: " . $e->getMessage(), [
                'pago_id' => $id,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al descargar comprobante: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtener datos bancarios de un consultor (AJAX)
     */
    public function obtenerDatosBancarios($consultorId)
    {
        try {
            $datosBancarios = \App\Models\DatosBancario::where('usuario_id', $consultorId)
                                                     ->orderBy('es_principal', 'desc')
                                                     ->get();
            
            return response()->json([
                'success' => true,
                'datos_bancarios' => $datosBancarios
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error al obtener datos bancarios: " . $e->getMessage(), [
                'consultor_id' => $consultorId,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos bancarios: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtener quincenas disponibles (para filtros y selección)
     */
    private function obtenerQuincenasDisponibles($meses = 6)
    {
        $quincenas = [];
        $fecha = Carbon::now();
        
        // Agregar quincena actual
        $quincenaActual = Pago::obtenerQuincenaActual();
        $quincenas[$quincenaActual] = $this->formatearQuincena($quincenaActual);
        
        // Agregar quincenas anteriores
        for ($i = 0; $i < $meses * 2; $i++) {
            // Retroceder 15 días
            $fecha = $fecha->copy()->subDays(15);
            $quincena = Pago::generarFormatoQuincena($fecha);
            
            // Evitar duplicados
            if (!isset($quincenas[$quincena])) {
                $quincenas[$quincena] = $this->formatearQuincena($quincena);
            }
        }
        
        return $quincenas;
    }
    
    /**
     * Formatear quincena para mostrar en la interfaz
     */
    private function formatearQuincena($quincena)
    {
        $partes = explode('-', $quincena);
        
        if (count($partes) != 3) {
            return $quincena;
        }
        
        $anio = $partes[0];
        $mes = $partes[1];
        $quincena = $partes[2];
        
        $nombresMeses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];
        
        $nombreMes = $nombresMeses[$mes] ?? $mes;
        $numeroQuincena = $quincena == '1' ? 'Primera' : 'Segunda';
        
        return "{$numeroQuincena} quincena de {$nombreMes} {$anio}";
    }
}