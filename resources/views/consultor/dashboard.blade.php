@extends('layouts.consultor')

@section('title', 'Dashboard Consultor - ALI3000')

@section('consultor-content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Horas del Mes -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Horas del Mes</p>
                <p class="text-3xl font-bold text-[#000000]">{{ number_format($horasMesActual, 1) }}</p>
            </div>
            <div class="w-12 h-12 bg-[#4682B4] bg-opacity-10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-[#4682B4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center">
            @php
                $color = $variacionHorasMes > 0 ? 'text-green-500' : ($variacionHorasMes < 0 ? 'text-red-500' : 'text-gray-400');
                $signo = $variacionHorasMes > 0 ? '+' : '';
            @endphp
            <span class="{{ $color }} text-sm font-medium">{{ $signo }}{{ number_format($variacionHorasMes, 1) }}%</span>
            <span class="text-[#708090] text-sm ml-2">vs mes anterior</span>
        </div>
    </div>

    <!-- Pagos Pendientes -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Pagos Pendientes</p>
                <p class="text-3xl font-bold text-[#000000]">${{ number_format($pagosPendientes, 2, '.', ',') }}</p>
            </div>
            <div class="w-12 h-12 bg-[#FF6347] bg-opacity-10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-[#FF6347]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center">
            <span class="text-[#FF6347] text-sm font-medium">2 pagos</span>
            <span class="text-[#708090] text-sm ml-2">en proceso</span>
        </div>
    </div>

    <!-- Tarifa Actual -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Tarifa por Hora</p>
                <p class="text-3xl font-bold text-[#000000]">${{ $tarifaPorHora }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center">
            <span class="text-green-500 text-sm font-medium">Nivel {{ ucfirst($nivelDesarrollo) }}</span>
        </div>
    </div>
</div>

<!-- Pagos Mensuales -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
    <h3 class="text-lg font-semibold text-[#000000] mb-4">Pagos Mensuales</h3>
    <canvas id="graficoPagosMensualesConsultor" height="80"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('graficoPagosMensualesConsultor').getContext('2d');
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
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Acciones Rápidas -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-[#000000] mb-4">Acciones Rápidas</h3>
        <div class="space-y-3">
            <a href="{{ route('consultor.horas.create') }}" class="flex items-center justify-between p-3 bg-[#4682B4] bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-[#4682B4] rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-[#000000]">Registrar Horas</span>
                </div>
            </a>
            
            <a href="{{ route('consultor.horas.index') }}" class="flex items-center justify-between p-3 bg-[#FF6347] bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-[#FF6347] rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-[#000000]">Ver Mis Horas</span>
                </div>
                <span class="text-xs bg-[#e3eaf2] text-[#4682B4] px-2 py-1 rounded-full font-bold">12</span>
            </a>
            
            <a href="{{ route('consultor.pagos.index') }}" class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-[#000000]">Ver Mis Pagos</span>
                </div>
                <span class="text-xs bg-[#e3eaf2] text-[#4682B4] px-2 py-1 rounded-full font-bold">3</span>
            </a>
        </div>
    </div>

    <!-- Resumen del Mes -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-[#000000] mb-4">Resumen del Mes</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-[#708090]">Horas Registradas</span>
                <span class="text-sm font-medium text-[#000000]">{{ number_format($horasRegistradas, 1) }} hrs</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[#708090]">Horas Aprobadas</span>
                <span class="text-sm font-medium text-green-600">{{ number_format($horasAprobadas, 1) }} hrs</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[#708090]">Horas Pendientes</span>
                <span class="text-sm font-medium text-yellow-600">{{ number_format($horasPendientes, 1) }} hrs</span>
            </div>
            <div class="border-t pt-4">
                <div class="flex items-center justify-between">
                    <span class="text-[#708090] font-medium">Total a Cobrar</span>
                    <span class="text-lg font-bold text-[#4682B4]">${{ number_format($totalACobrar, 2, '.', ',') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection