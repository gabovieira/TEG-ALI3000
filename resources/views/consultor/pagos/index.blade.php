@extends('layouts.consultor')

@section('title', 'Mis Pagos - ALI3000')

@section('page-title', 'Mis Pagos')

@section('consultor-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#000000]">Mis Pagos</h1>
    <p class="text-[#708090]">Gestiona tus pagos y confirmaciones</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Resumen de Pagos -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div class="ml-5">
                <h3 class="text-lg font-medium text-gray-900">Pendientes de Confirmación</h3>
                <div class="mt-1 text-3xl font-semibold text-blue-600">{{ $pendientesConfirmacion }}</div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="ml-5">
                <h3 class="text-lg font-medium text-gray-900">Confirmados</h3>
                <div class="mt-1 text-3xl font-semibold text-green-600">{{ $confirmados }}</div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-gray-100 rounded-md p-3">
                <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-5">
                <h3 class="text-lg font-medium text-gray-900">Total Pagos</h3>
                <div class="mt-1 text-3xl font-semibold text-gray-600">{{ $totalPagos }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones Rápidas -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Acciones Rápidas</h2>
    
    <div class="flex flex-wrap gap-4">
        @if($pendientesConfirmacion > 0)
            <a href="{{ route('consultor.pagos.index', ['estado' => 'pagado']) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Confirmar Pagos Pendientes ({{ $pendientesConfirmacion }})
            </a>
        @endif
        
        <!-- Botones de filtro rápido -->
        <a href="{{ route('consultor.pagos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4] {{ !request('estado') ? 'ring-2 ring-[#4682B4] bg-blue-50' : '' }}">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
            Todos ({{ $totalPagos }})
        </a>
        
        <a href="{{ route('consultor.pagos.index', ['estado' => 'confirmado']) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4] {{ request('estado') == 'confirmado' ? 'ring-2 ring-[#4682B4] bg-blue-50' : '' }}">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Confirmados ({{ $confirmados }})
        </a>
    </div>
</div>

<!-- Filtros Avanzados (Colapsable) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="p-4 border-b border-gray-200">
        <button type="button" onclick="toggleFilters()" class="flex items-center justify-between w-full text-left">
            <h3 class="text-md font-medium text-[#000000]">Filtros Avanzados</h3>
            <svg id="filter-icon" class="h-5 w-5 text-gray-400 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    
    <div id="filter-content" class="p-4 hidden">
        <form action="{{ route('consultor.pagos.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="estado" name="estado" class="rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ $filtroEstado == 'pendiente' ? 'selected' : '' }}>Pendiente de Pago</option>
                    <option value="pagado" {{ $filtroEstado == 'pagado' ? 'selected' : '' }}>Pendiente Confirmación</option>
                    <option value="confirmado" {{ $filtroEstado == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                    <option value="rechazado" {{ $filtroEstado == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    <option value="anulado" {{ $filtroEstado == 'anulado' ? 'selected' : '' }}>Anulado</option>
                </select>
            </div>
            
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#4682B4] hover:bg-[#36648B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                    Aplicar Filtros
                </button>
            </div>
            
            @if($filtroEstado)
                <div>
                    <a href="{{ route('consultor.pagos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Limpiar Filtros
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

<script>
function toggleFilters() {
    const content = document.getElementById('filter-content');
    const icon = document.getElementById('filter-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}
</script>

<!-- Lista de Pagos -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-medium text-[#000000]">Mis Pagos</h2>
        <p class="text-sm text-gray-500 mt-1">
            @if($filtroEstado)
                Mostrando pagos con estado: <span class="font-medium">{{ ucfirst($filtroEstado) }}</span>
            @else
                Mostrando todos los pagos
            @endif
        </p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto Neto (USD)</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto Neto (Bs)</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Procesado</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pagos as $pago)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $pago->nombre_quincena }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($pago->detalles->count() > 0)
                                @foreach($pago->detalles as $detalle)
                                    {{ $detalle->empresa->nombre }}@if(!$loop->last), @endif
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                            {{ $pago->total_horas ?? $pago->horas ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($pago->monto_neto ?? $pago->total_menos_islr_divisas ?? 0, 2) }}
                            <div class="text-xs text-gray-500">
                                (después de retenciones)
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Bs {{ number_format(($pago->monto_neto ?? $pago->total_menos_islr_divisas ?? 0) * ($pago->tasa_cambio ?? 0), 2) }}
                            @if($pago->tasa_cambio)
                                <div class="text-xs text-gray-500">
                                    ({{ number_format($pago->tasa_cambio, 4) }} Bs/USD)
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($pago->fecha_pago)
                                {{ $pago->fecha_pago->format('d/m/Y') }}
                            @elseif($pago->created_at)
                                {{ $pago->created_at->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pago->estado == 'pendiente')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pendiente de Pago
                                </span>
                            @elseif($pago->estado == 'pagado')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Pendiente Confirmación
                                </span>
                            @elseif($pago->estado == 'confirmado')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Confirmado
                                </span>
                            @elseif($pago->estado == 'rechazado')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rechazado
                                </span>
                            @elseif($pago->estado == 'anulado')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Anulado
                                </span>
                            @elseif($pago->estado == 'procesado')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Pendiente Confirmación
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $pago->estado ?? 'Sin estado' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('consultor.pagos.show', $pago->id) }}" class="text-[#4682B4] hover:text-blue-800 mr-3">
                                Ver
                            </a>
                            
                            @if($pago->estado == 'pagado')
                                <a href="{{ route('consultor.pagos.confirmar.form', $pago->id) }}" class="text-green-600 hover:text-green-800">
                                    Confirmar
                                </a>
                            @endif
                            
                            @if($pago->comprobante_pago)
                                <a href="{{ route('consultor.pagos.comprobante', $pago->id) }}" target="_blank" class="text-gray-600 hover:text-gray-800 ml-3">
                                    Comprobante
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No hay pagos que coincidan con los filtros aplicados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $pagos->withQueryString()->links() }}
    </div>
</div>
@endsection