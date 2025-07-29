@extends('layouts.admin')

@section('title', 'Dashboard Administrativo - ALI3000')

@section('page-title', 'Dashboard')

@section('admin-content')
<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Mensajes de éxito/error -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    </div>
@endif

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Consultores Activos -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Consultores Activos</p>
                <p class="text-3xl font-bold text-[#000000]">{{ $consultoresActivos }}</p>
            </div>
            <div class="w-12 h-12 bg-[#4682B4] bg-opacity-10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-[#4682B4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center">
            @if($crecimientoConsultores > 0)
                <span class="text-green-500 text-sm font-medium">+{{ $crecimientoConsultores }}%</span>
            @elseif($crecimientoConsultores < 0)
                <span class="text-red-500 text-sm font-medium">{{ $crecimientoConsultores }}%</span>
            @else
                <span class="text-gray-500 text-sm font-medium">0%</span>
            @endif
            <span class="text-[#708090] text-sm ml-2">vs mes anterior</span>
        </div>
    </div>

    <!-- Horas Pendientes -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Horas Pendientes de Aprobación</p>
                <p class="text-3xl font-bold text-[#000000]">{{ number_format($horasPendientes, 1) }}</p>
            </div>
            <div class="w-12 h-12 bg-[#FF6347] bg-opacity-10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-[#FF6347]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center">
            @if($horasPendientes > 0)
                <span class="text-[#FF6347] text-sm font-medium">Requiere atención</span>
                <span class="text-[#708090] text-xs ml-2">(Horas reportadas por consultores pendientes de aprobar)</span>
            @else
                <span class="text-green-500 text-sm font-medium">Todo al día</span>
            @endif
        </div>
    </div>

    <!-- Pagos del Mes -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Pagos Procesados del Mes</p>
                <p class="text-3xl font-bold text-[#000000]">${{ number_format($totalPagosMesActual ?? $pagosMesActual ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center">
            @if($crecimientoPagos > 0)
                <span class="text-green-500 text-sm font-medium">+{{ $crecimientoPagos }}%</span>
            @elseif($crecimientoPagos < 0)
                <span class="text-red-500 text-sm font-medium">{{ $crecimientoPagos }}%</span>
            @else
                <span class="text-gray-500 text-sm font-medium">0%</span>
            @endif
            <span class="text-[#708090] text-sm ml-2">vs mes anterior</span>
        </div>
        <div class="mt-2">
            <span class="text-[#708090] text-xs">Solo incluye pagos con estado "pagado" del mes actual</span>
            @if(config('app.debug'))
                <div class="text-xs text-gray-400 mt-1">
                    Debug: {{ $pagosRecientes->count() }} pagos encontrados
                </div>
            @endif
        </div>
    </div>

    <!-- Tasa BCV -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-2">
                    <p class="text-[#708090] text-sm font-medium">Tasa BCV (USD)</p>
                    <button onclick="actualizarTasaApi()" class="text-[#4682B4] hover:text-[#FF6347] transition-colors" title="Actualizar desde API">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    <button onclick="actualizarTasa()" class="text-[#708090] hover:text-[#4682B4] transition-colors" title="Actualizar manualmente">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                </div>
                @if($tasaBcv)
                    <p class="text-3xl font-bold text-[#000000]">{{ number_format($tasaBcv->tasa, 2) }}</p>
                @else
                    <p class="text-3xl font-bold text-red-500">N/A</p>
                @endif
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center">
            @if($tasaBcv)
                @php
                    $esHoy = $tasaBcv->fecha_registro->isToday();
                    $diasDiferencia = $tasaBcv->fecha_registro->diffInDays(now());
                @endphp
                <div class="flex items-center space-x-2">
                    @if($esHoy)
                        <span class="text-green-500 text-sm font-medium">Actualizada hoy</span>
                    @else
                        <span class="text-orange-500 text-sm font-medium">
                            {{ $diasDiferencia == 1 ? 'Actualizada ayer' : 'Del ' . $tasaBcv->fecha_registro->format('d/m/Y') }}
                        </span>
                    @endif

                </div>
            @else
                <span class="text-red-500 text-sm font-medium">Sin datos disponibles</span>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Acciones Rápidas -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-[#000000] mb-4">Acciones Rápidas</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.horas.index') }}" class="flex items-center justify-between p-3 bg-[#4682B4] bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-[#4682B4] rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-[#000000]">Aprobar Horas</span>
                </div>
                <span class="text-xs bg-[#FF6347] text-white px-2 py-1 rounded-full font-bold">{{ number_format($horasPendientes, 1) }}</span>
            </a>
            
            <a href="{{ route('admin.pagos.generar') }}" class="flex items-center justify-between p-3 bg-[#FF6347] bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-[#FF6347] rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-[#000000]">Generar Pagos</span>
                </div>
            </a>
            
            <a href="{{ route('admin.tokens.index') }}" class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-[#000000]">Registrar Usuarios</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Horas Pendientes de Aprobación -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-[#000000] mb-4">Horas Pendientes de Aprobación</h3>
        <div class="space-y-3">
            @forelse($horasPendientesDetalle as $hora)
                @php
                    $fechaHora = $hora->fecha;
                    $esQuincenaActual = $fechaHora->day <= 15 && $fechaHora->month == now()->month && $fechaHora->year == now()->year;
                    $esSegundaQuincenaActual = $fechaHora->day > 15 && $fechaHora->month == now()->month && $fechaHora->year == now()->year;
                    
                    if (now()->day <= 15) {
                        $quincenaLabel = $esQuincenaActual ? 'Quincena Actual' : 'Quincena Anterior';
                    } else {
                        $quincenaLabel = $esSegundaQuincenaActual ? 'Quincena Actual' : 'Quincena Anterior';
                    }
                @endphp
                <div class="p-3 bg-gray-50 rounded-lg border-l-4 border-orange-400">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <p class="font-medium text-[#000000]">{{ $hora->usuario->primer_nombre }} {{ $hora->usuario->primer_apellido }}</p>
                                <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">{{ $quincenaLabel }}</span>
                            </div>
                            <p class="text-sm text-[#708090] mt-1">{{ $hora->empresa->nombre ?? 'Sin empresa' }}</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="text-sm font-medium text-blue-600">{{ number_format($hora->total_horas, 1) }} horas</span>
                                <span class="text-xs text-[#708090]">{{ $hora->fecha->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="w-3 h-3 bg-orange-400 rounded-full"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <p class="text-[#708090]">No hay horas pendientes de aprobación en las últimas dos quincenas</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-4 border-t pt-3">
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500">Mostrando horas de quincena actual y anterior</span>
                <a href="{{ route('admin.horas.index') }}" class="text-[#4682B4] hover:text-[#FF6347] font-medium text-sm">Ver todas las horas pendientes →</a>
            </div>
        </div>
    </div>

    <!-- Pagos Recientes -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-[#000000] mb-4">Pagos Procesados Recientes</h3>
        <div class="space-y-3">
            @forelse($pagosRecientes as $pago)
                <div class="flex items-center justify-between p-3 
                    @if($pago->estado == 'pagado') bg-green-50 
                    @elseif($pago->estado == 'pendiente') bg-yellow-50 
                    @else bg-gray-50 @endif rounded-lg">
                    <div>
                        <p class="font-medium text-[#000000]">{{ $pago->consultor->primer_nombre }} {{ $pago->consultor->primer_apellido }}</p>
                        <p class="text-sm text-[#708090]">{{ $pago->quincena }}</p>
                        <p class="text-xs text-[#708090]">{{ $pago->fecha_pago ? $pago->fecha_pago->format('d/m/Y') : 'Pendiente' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold 
                            @if($pago->estado == 'pagado') text-green-600 
                            @elseif($pago->estado == 'pendiente') text-yellow-600 
                            @else text-gray-600 @endif">
                            ${{ number_format($pago->monto_neto ?? $pago->total_menos_islr_divisas ?? 0, 2) }}
                        </p>
                        <p class="text-xs text-[#708090] capitalize">{{ $pago->estado }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <p class="text-[#708090]">No hay pagos procesados este mes</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-4 border-t pt-3">
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500">Solo pagos del mes actual con estado "pagado"</span>
                <a href="{{ route('admin.pagos.index') }}" class="text-[#4682B4] hover:text-[#FF6347] font-medium text-sm">Ver todos los pagos →</a>
            </div>
        </div>
    </div>
</div>

<!-- Calendario y Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Calendario Quincena Actual -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-[#000000] mb-4">Calendario - Quincena Actual</h3>
        
        <!-- Header del Calendario -->
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-medium text-[#708090]">
                @php
                    $meses = [
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                    ];
                    $mesActual = $meses[now()->month];
                    $añoActual = now()->year;
                @endphp
                {{ $mesActual }} {{ $añoActual }}
            </h4>
            <div class="text-xs text-[#4682B4] font-medium">
                @php
                    $day = now()->day;
                    $quincena = $day <= 15 ? '1ra Quincena' : '2da Quincena';
                @endphp
                {{ $quincena }}
            </div>
        </div>
        
        <!-- Grid del Calendario -->
        <div class="grid grid-cols-7 gap-1 text-center text-xs">
            <!-- Headers de días -->
            <div class="p-2 font-medium text-[#708090]">Dom</div>
            <div class="p-2 font-medium text-[#708090]">Lun</div>
            <div class="p-2 font-medium text-[#708090]">Mar</div>
            <div class="p-2 font-medium text-[#708090]">Mié</div>
            <div class="p-2 font-medium text-[#708090]">Jue</div>
            <div class="p-2 font-medium text-[#708090]">Vie</div>
            <div class="p-2 font-medium text-[#708090]">Sáb</div>
            
            @php
                $today = now();
                $startOfMonth = $today->copy()->startOfMonth();
                $endOfMonth = $today->copy()->endOfMonth();
                $startDate = $startOfMonth->copy()->startOfWeek();
                $endDate = $endOfMonth->copy()->endOfWeek();
                
                // Determinar rango de quincena actual
                $currentDay = $today->day;
                $quincenaStart = $currentDay <= 15 ? 1 : 16;
                $quincenaEnd = $currentDay <= 15 ? 15 : $endOfMonth->day;
                
                // Obtener feriados del mes actual desde la base de datos
                $feriadosDelMes = \App\Models\Feriado::delMes($today->year, $today->month)->get();
                $feriados = $feriadosDelMes->pluck('fecha')->map(function($fecha) {
                    return $fecha->format('Y-m-d');
                })->toArray();
                $feriadosInfo = $feriadosDelMes->keyBy(function($feriado) {
                    return $feriado->fecha->format('Y-m-d');
                });
                
                // Debug: Agregar feriados manualmente para julio si no están
                if ($today->month == 7) {
                    $feriados = array_merge($feriados, ['2025-07-05', '2025-07-24']);
                }
            @endphp
            
            @for($date = $startDate; $date <= $endDate; $date->addDay())
                @php
                    $isCurrentMonth = $date->month === $today->month;
                    $isToday = $date->isSameDay($today);
                    $isInQuincena = $isCurrentMonth && $date->day >= $quincenaStart && $date->day <= $quincenaEnd;
                    $isFeriado = in_array($date->format('Y-m-d'), $feriados);
                    $isWeekend = $date->isWeekend();
                @endphp
                
                <div class="p-2 rounded relative min-h-[32px] flex items-center justify-center
                    @if($isToday) 
                        text-white font-bold shadow-md
                    @elseif($isFeriado) 
                        text-red-600 font-medium
                    @elseif($isInQuincena) 
                        text-blue-700 font-semibold
                    @elseif($isWeekend) 
                        text-gray-500
                    @elseif(!$isCurrentMonth) 
                        text-gray-300
                    @else 
                        text-gray-900 hover:bg-gray-50
                    @endif
                " style="
                    @if($isToday) 
                        background-color: #4682B4;
                    @elseif($isFeriado) 
                        background-color: #fee2e2; border: 1px solid #ef4444;
                    @elseif($isInQuincena) 
                        background-color: #dbeafe; border: 1px solid #3b82f6;
                    @elseif($isWeekend) 
                        background-color: #f9fafb;
                    @endif
                ">
                    {{ $date->day }}
                    @if($isFeriado)
                        <div class="absolute top-0 right-0 w-2 h-2 bg-[#FF6347] rounded-full"></div>
                    @endif
                </div>
            @endfor
        </div>
        
        <!-- Leyenda -->
        <div class="mt-4 space-y-2 text-xs">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded" style="background-color: #4682B4;"></div>
                <span class="text-[#708090]">Hoy</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded border" style="background-color: #dbeafe; border-color: #3b82f6;"></div>
                <span class="text-[#708090]">Quincena Actual</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded border relative" style="background-color: #fee2e2; border-color: #ef4444;">
                    <div class="absolute top-0 right-0 w-1 h-1 rounded-full" style="background-color: #ef4444;"></div>
                </div>
                <span class="text-[#708090]">Días Feriados</span>
            </div>
        </div>
        
        <!-- Info Quincena -->
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <div class="text-xs text-[#708090] mb-1">Período Actual:</div>
            <div class="text-sm font-medium text-[#000000]">
                {{ $quincenaStart }} - {{ $quincenaEnd }} de {{ $mesActual }}
            </div>
            <div class="text-xs text-[#708090] mt-1">
                @php
                    $diasRestantes = $quincenaEnd - $today->day;
                    $diasRestantes = $diasRestantes < 0 ? 0 : $diasRestantes;
                @endphp
                {{ $diasRestantes }} días restantes
            </div>
        </div>
    </div>

<!-- Gráficos y Métricas -->
<div class="lg:col-span-2 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Gráfico de Pagos Mensuales -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
        <h2 class="text-lg font-medium text-[#000000] mb-4">Pagos Mensuales</h2>
        <div style="height:320px;">
            <canvas id="pagosMensualesChart"></canvas>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('pagosMensualesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labelsMeses ?? []),
                datasets: [
                    {
                        label: 'Total',
                        data: @json($pagosTotales ?? []),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.1)',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 4,
                        borderWidth: 2,
                    },
                    {
                        label: 'Pagados',
                        data: @json($pagosPagados ?? []),
                        borderColor: '#f59e42',
                        backgroundColor: 'rgba(245,158,66,0.1)',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 4,
                        borderWidth: 2,
                    },
                    {
                        label: 'Confirmados',
                        data: @json($pagosConfirmados ?? []),
                        borderColor: '#6b7280',
                        backgroundColor: 'rgba(107,114,128,0.1)',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 4,
                        borderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
    </script>

    <!-- Consultores por Estado -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-[#000000] mb-4">Consultores por Estado</h3>
        <div class="space-y-4">
            @php
                $totalConsultores = $consultoresStats['activos'] + $consultoresStats['pendientes'] + $consultoresStats['inactivos'];
                $porcentajeActivos = $totalConsultores > 0 ? ($consultoresStats['activos'] / $totalConsultores) * 100 : 0;
                $porcentajePendientes = $totalConsultores > 0 ? ($consultoresStats['pendientes'] / $totalConsultores) * 100 : 0;
                $porcentajeInactivos = $totalConsultores > 0 ? ($consultoresStats['inactivos'] / $totalConsultores) * 100 : 0;
            @endphp
            
            <div class="flex items-center justify-between">
                <span class="text-[#708090]">Activos</span>
                <div class="flex items-center space-x-2">
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $porcentajeActivos }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-[#000000]">{{ $consultoresStats['activos'] }}</span>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <span class="text-[#708090]">Pendientes</span>
                <div class="flex items-center space-x-2">
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $porcentajePendientes }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-[#000000]">{{ $consultoresStats['pendientes'] }}</span>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <span class="text-[#708090]">Inactivos</span>
                <div class="flex items-center space-x-2">
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $porcentajeInactivos }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-[#000000]">{{ $consultoresStats['inactivos'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para actualizar Tasa BCV -->
<div id="modalTasaBcv" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actualizar Tasa BCV</h3>
                <form action="{{ route('admin.actualizar-tasa-bcv') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="tasa" class="block text-sm font-medium text-gray-700 mb-2">Tasa BCV (Bs/USD)</label>
                        <input type="number" step="0.0001" name="tasa" id="tasa" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ $tasaBcv->tasa ?? '' }}" required>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="cerrarModalTasa()" 
                                class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function actualizarTasa() {
    document.getElementById('modalTasaBcv').classList.remove('hidden');
}

function actualizarTasaApi() {
    if (confirm('¿Actualizar la tasa BCV desde la API? Esto puede tomar unos segundos.')) {
        // Crear formulario para enviar POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.actualizar-tasa-api") }}';
        
        // Agregar token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Enviar formulario
        document.body.appendChild(form);
        form.submit();
    }
}

function cerrarModalTasa() {
    document.getElementById('modalTasaBcv').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalTasaBcv').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalTasa();
    }
});
</script>
<!-- Script para inicializar los gráficos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Pagos Mensuales
    const pagosMensualesCtx = document.getElementById('pagosMensualesChart');
    
    if (pagosMensualesCtx) {
        const ctx = pagosMensualesCtx.getContext('2d');
        
        // Datos de pagos mensuales desde el controlador
        const pagosMensualesData = {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Pagos (USD)',
                data: {{ json_encode($datosPagosMensuales ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) }},
                backgroundColor: 'rgba(70, 130, 180, 0.2)',
                borderColor: 'rgba(70, 130, 180, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        };
        
        new Chart(ctx, {
            type: 'line',
            data: pagosMensualesData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return 'USD ' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error('No se encontró el elemento canvas para el gráfico de pagos mensuales');
    }$' + value;
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error('No se encontró el elemento canvas para el gráfico de pagos mensuales');
    }
});
</script>
@endsection