<?php

namespace App\Http\Controllers\Consultor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $usuarioId = Auth::id();
        $pagosTotales = [];
        $pagosPagados = [];
        $pagosConfirmados = [];
        $labelsMeses = [];
        $fechaIter = Carbon::now()->copy()->startOfMonth()->subMonths(11);
        $mesesES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        for ($i = 0; $i < 12; $i++) {
            $mes = $fechaIter->month;
            $anio = $fechaIter->year;
            $labelsMeses[] = $mesesES[$fechaIter->month - 1];
            $pagosTotales[] = Pago::where('usuario_id', $usuarioId)
                ->whereMonth('fecha_pago', $mes)
                ->whereYear('fecha_pago', $anio)
                ->whereIn('estado', ['pagado', 'confirmado'])
                ->sum('monto_neto');
            $pagosPagados[] = Pago::where('usuario_id', $usuarioId)
                ->whereMonth('fecha_pago', $mes)
                ->whereYear('fecha_pago', $anio)
                ->where('estado', 'pagado')
                ->sum('monto_neto');
            $pagosConfirmados[] = Pago::where('usuario_id', $usuarioId)
                ->whereMonth('fecha_pago', $mes)
                ->whereYear('fecha_pago', $anio)
                ->where('estado', 'confirmado')
                ->sum('monto_neto');
            $fechaIter->addMonth();
        }
        // Calcular pagos pendientes (estado 'pagado')
        $pagosPendientes = Pago::where('usuario_id', $usuarioId)
            ->where('estado', 'pagado')
            ->sum('monto_neto');

        // Calcular horas del mes actual
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;
        $horasMesActual = \App\Models\RegistroHoras::where('usuario_id', $usuarioId)
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->sum('horas_trabajadas');

        // Calcular horas del mes anterior
        $mesAnterior = Carbon::now()->subMonth();
        $horasMesAnterior = \App\Models\RegistroHoras::where('usuario_id', $usuarioId)
            ->whereMonth('fecha', $mesAnterior->month)
            ->whereYear('fecha', $mesAnterior->year)
            ->sum('horas_trabajadas');

        // Calcular variación porcentual
        if ($horasMesAnterior > 0) {
            $variacionHorasMes = round((($horasMesActual - $horasMesAnterior) / $horasMesAnterior) * 100, 1);
        } else {
            $variacionHorasMes = $horasMesActual > 0 ? 100 : 0;
        }

        // Resumen del Mes
        // Horas registradas: todas las del mes actual
        $horasRegistradas = \App\Models\RegistroHoras::where('usuario_id', $usuarioId)
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->sum('horas_trabajadas');
        // Horas aprobadas: solo las aprobadas
        $horasAprobadas = \App\Models\RegistroHoras::where('usuario_id', $usuarioId)
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->where('estado', 'aprobado')
            ->sum('horas_trabajadas');
        // Horas pendientes: solo las pendientes
        $horasPendientes = \App\Models\RegistroHoras::where('usuario_id', $usuarioId)
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->where('estado', 'pendiente')
            ->sum('horas_trabajadas');
        // Total a cobrar: suma de pagos confirmados y pagados del mes actual
        $totalACobrar = \App\Models\Pago::where('usuario_id', $usuarioId)
            ->whereMonth('fecha_pago', $mesActual)
            ->whereYear('fecha_pago', $anioActual)
            ->whereIn('estado', ['pagado', 'confirmado'])
            ->sum('monto_neto');

        // Obtener la tarifa por hora del usuario desde sus datos laborales
        $tarifaPorHora = auth()->user()->datosLaborales ? number_format(auth()->user()->datosLaborales->tarifa_por_hora ?? 0, 2, '.', '') : '0.00';
        $nivelDesarrollo = auth()->user()->datosLaborales->nivel_desarrollo ?? 'No especificado';
        
        return view('consultor.dashboard', compact(
            'pagosTotales',
            'pagosPagados',
            'pagosConfirmados',
            'labelsMeses',
            'pagosPendientes',
            'tarifaPorHora',
            'nivelDesarrollo',
            'horasMesActual',
            'horasMesAnterior',
            'variacionHorasMes',
            'horasRegistradas',
            'horasAprobadas',
            'horasPendientes',
            'totalACobrar'
        ));
    }
}
