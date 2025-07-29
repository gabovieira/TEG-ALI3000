<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\RegistroHoras;
use App\Models\Pago;
use App\Models\TasaBcv;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Obtener estadísticas reales
        $consultoresActivos = Usuario::where('tipo_usuario', 'consultor')
                                   ->where('estado', 'activo')
                                   ->count();

        $horasPendientes = RegistroHoras::where('estado', 'pendiente')->sum('horas_trabajadas');

        // Pagos del mes actual
        $pagosMesActual = Pago::whereMonth('fecha_pago', Carbon::now()->month)
                             ->whereYear('fecha_pago', Carbon::now()->year)
                             ->whereIn('estado', ['pagado', 'confirmado'])
                             ->sum('monto_neto'); // Usando monto_neto para consistencia

        // Tasa BCV más reciente (siempre la más actual disponible)
        $tasaBcv = TasaBcv::reciente();

        // Horas pendientes de la quincena actual y anterior
        $hoy = Carbon::now();
        $quincenaActual = $hoy->day <= 15 ? 1 : 2;
        $mesActual = $hoy->month;
        $anioActual = $hoy->year;
        
        // Calcular quincena anterior
        if ($quincenaActual == 1) {
            $quincenaAnterior = 2;
            $mesAnterior = $mesActual == 1 ? 12 : $mesActual - 1;
            $anioAnterior = $mesActual == 1 ? $anioActual - 1 : $anioActual;
        } else {
            $quincenaAnterior = 1;
            $mesAnterior = $mesActual;
            $anioAnterior = $anioActual;
        }
        
        // Fechas de quincena actual
        if ($quincenaActual == 1) {
            $inicioQuincenaActual = Carbon::create($anioActual, $mesActual, 1);
            $finQuincenaActual = Carbon::create($anioActual, $mesActual, 15);
        } else {
            $inicioQuincenaActual = Carbon::create($anioActual, $mesActual, 16);
            $finQuincenaActual = Carbon::create($anioActual, $mesActual)->endOfMonth();
        }
        
        // Fechas de quincena anterior
        if ($quincenaAnterior == 1) {
            $inicioQuincenaAnterior = Carbon::create($anioAnterior, $mesAnterior, 1);
            $finQuincenaAnterior = Carbon::create($anioAnterior, $mesAnterior, 15);
        } else {
            $inicioQuincenaAnterior = Carbon::create($anioAnterior, $mesAnterior, 16);
            $finQuincenaAnterior = Carbon::create($anioAnterior, $mesAnterior)->endOfMonth();
        }
        
        // Obtener horas pendientes de ambas quincenas agrupadas por usuario y empresa
        $horasPendientesDetalle = RegistroHoras::with(['usuario', 'empresa'])
                                              ->select('usuario_id', 'empresa_id', 'fecha', DB::raw('SUM(horas_trabajadas) as total_horas'))
                                              ->where('estado', 'pendiente')
                                              ->where(function($query) use ($inicioQuincenaActual, $finQuincenaActual, $inicioQuincenaAnterior, $finQuincenaAnterior) {
                                                  $query->whereBetween('fecha', [$inicioQuincenaActual, $finQuincenaActual])
                                                        ->orWhereBetween('fecha', [$inicioQuincenaAnterior, $finQuincenaAnterior]);
                                              })
                                              ->groupBy('usuario_id', 'empresa_id', 'fecha')
                                              ->orderBy('fecha', 'desc')
                                              ->limit(8)
                                              ->get();

        // Pagos recientes (solo pagados y del mes actual)
        $pagosRecientes = Pago::with('consultor')
                             ->whereIn('estado', ['pagado', 'confirmado'])
                             ->whereMonth('fecha_pago', Carbon::now()->month)
                             ->whereYear('fecha_pago', Carbon::now()->year)
                             ->orderBy('fecha_pago', 'desc')
                             ->limit(5)
                             ->get();

        // Estadísticas de consultores por estado
        $consultoresStats = [
            'activos' => Usuario::where('tipo_usuario', 'consultor')->where('estado', 'activo')->count(),
            'pendientes' => Usuario::where('tipo_usuario', 'consultor')->where('estado', 'pendiente_registro')->count(),
            'inactivos' => Usuario::where('tipo_usuario', 'consultor')->where('estado', 'inactivo')->count(),
        ];

        // Comparación con mes anterior para consultores
        $mesAnterior = Carbon::now()->copy()->subMonth();
        $consultoresMesAnterior = Usuario::where('tipo_usuario', 'consultor')
                                        ->where('estado', 'activo')
                                        ->whereMonth('fecha_creacion', $mesAnterior->month)
                                        ->whereYear('fecha_creacion', $mesAnterior->year)
                                        ->count();
        
        $crecimientoConsultores = $consultoresMesAnterior > 0 
            ? round((($consultoresActivos - $consultoresMesAnterior) / $consultoresMesAnterior) * 100, 1)
            : 0;

        // Comparación pagos mes anterior
        $mesAnteriorPagos = Carbon::now()->copy()->subMonth();
        $pagosMesAnterior = Pago::whereMonth('fecha_pago', $mesAnteriorPagos->month)
                               ->whereYear('fecha_pago', $mesAnteriorPagos->year)
                               ->whereIn('estado', ['pagado', 'confirmado'])
                               ->sum('monto_neto'); // Usando monto_neto para consistencia

        $crecimientoPagos = $pagosMesAnterior > 0 
            ? round((($pagosMesActual - $pagosMesAnterior) / $pagosMesAnterior) * 100, 1)
            : 0;
            
        // Datos para el gráfico de pagos mensuales (últimos 12 meses)
        $pagosTotales = [];
        $pagosPagados = [];
        $pagosConfirmados = [];
        $labelsMeses = [];
        $fechaIter = Carbon::now()->copy()->startOfMonth()->subMonths(11);
        for ($i = 0; $i < 12; $i++) {
            $mes = $fechaIter->month;
            $anio = $fechaIter->year;
            $mesesES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            $labelsMeses[] = $mesesES[$fechaIter->month - 1];
            $pagosTotales[] = Pago::whereMonth('fecha_pago', $mes)
                ->whereYear('fecha_pago', $anio)
                ->whereIn('estado', ['pagado', 'confirmado'])
                ->sum('monto_neto');
            $pagosPagados[] = Pago::whereMonth('fecha_pago', $mes)
                ->whereYear('fecha_pago', $anio)
                ->where('estado', 'pagado')
                ->sum('monto_neto');
            $pagosConfirmados[] = Pago::whereMonth('fecha_pago', $mes)
                ->whereYear('fecha_pago', $anio)
                ->where('estado', 'confirmado')
                ->sum('monto_neto');
            $fechaIter->addMonth();
        }
        
        // Calcular el total de pagos del mes actual para verificación
        $totalPagosMesActual = Pago::whereMonth('fecha_pago', Carbon::now()->month)
                                  ->whereYear('fecha_pago', Carbon::now()->year)
                                  ->whereIn('estado', ['pagado', 'confirmado'])
                                  ->sum('monto_neto');

        // Calcular resumen mensual (últimos 12 meses)
        $resumenMensual = Pago::selectRaw("DATE_FORMAT(fecha_pago, '%Y-%m') as mes, SUM(monto_neto) as total")
            ->whereIn('estado', ['pagado', 'confirmado'])
            ->whereNotNull('fecha_pago')
            ->where('fecha_pago', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'mes' => $item->mes,
                    'total' => round($item->total, 2)
                ];
            });

        return view('admin.dashboard', compact(
            'consultoresActivos',
            'horasPendientes', 
            'pagosMesActual',
            'tasaBcv',
            'horasPendientesDetalle',
            'pagosRecientes',
            'consultoresStats',
            'crecimientoConsultores',
            'crecimientoPagos',
            'pagosTotales',
            'pagosPagados',
            'pagosConfirmados',
            'totalPagosMesActual',
            'resumenMensual',
            'labelsMeses'
        ));
    }

    /**
     * Actualizar tasa BCV manualmente
     */
    public function actualizarTasaBcv(Request $request)
    {
        $request->validate([
            'tasa' => 'required|numeric|min:0',
        ]);

        $tasaHoy = TasaBcv::where('fecha_registro', Carbon::today())->first();

        if ($tasaHoy) {
            $tasaHoy->update([
                'tasa' => $request->tasa,
            ]);
        } else {
            TasaBcv::create([
                'tasa' => $request->tasa,
                'fecha_registro' => Carbon::today(),
                'origen' => 'Manual',
            ]);
        }

        return redirect()->back()->with('success', 'Tasa BCV actualizada correctamente');
    }

    /**
     * Actualizar tasa BCV desde API
     */
    public function actualizarTasaApi()
    {
        try {
            $tasaBcvService = new \App\Services\TasaBcvService();
            $tasa = $tasaBcvService->actualizarTasa();
            
            return redirect()->back()->with('success', 
                "Tasa BCV actualizada: {$tasa->tasa} Bs/USD"
            );
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 
                'Error actualizando tasa desde API: ' . $e->getMessage()
            );
        }
    }
}