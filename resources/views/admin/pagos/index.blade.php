@extends('layouts.admin')

@section('title', 'Gestión de Pagos - ALI3000')

@section('page-title', 'Gestión de Pagos')

@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#000000]">Gestión de Pagos</h1>
    <p class="text-[#708090]">Administra los pagos a consultores</p>
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

@if(session('warning'))
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
        {{ session('warning') }}
    </div>
@endif

<!-- Resultados de generación de pagos (si existen) -->
@if(session('resultados'))
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <h2 class="text-lg font-medium text-[#000000] mb-4">Resultados de Generación de Pagos</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-sm text-blue-700">Total Generados</p>
                <p class="text-2xl font-bold text-blue-800">{{ session('resultados')['total_generados'] }}</p>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-sm text-blue-700">Total Consultores</p>
                <p class="text-2xl font-bold text-blue-800">{{ session('resultados')['total_consultores'] }}</p>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-sm text-blue-700">Total Horas</p>
                <p class="text-2xl font-bold text-blue-800">{{ session('resultados')['total_horas'] }}</p>
            </div>
        </div>
        
        @if(count(session('resultados')['errores']) > 0)
            <div class="mt-4">
                <h3 class="text-md font-medium text-red-800 mb-2">Errores ({{ count(session('resultados')['errores']) }})</h3>
                <ul class="list-disc pl-5 text-sm text-red-700">
                    @foreach(session('resultados')['errores'] as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif

<!-- Filtros -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Filtros</h2>
    
    <form action="{{ route('admin.pagos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Estado -->
        <div>
            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select id="estado" name="estado" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                <option value="">Todos</option>
                <option value="pendiente" {{ $filtros['estado'] == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="pagado" {{ $filtros['estado'] == 'pagado' ? 'selected' : '' }}>Pagado</option>
                <option value="anulado" {{ $filtros['estado'] == 'anulado' ? 'selected' : '' }}>Anulado</option>
                <option value="confirmado" {{ $filtros['estado'] == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
            </select>
        </div>
        
        <!-- Consultor -->
        <div>
            <label for="consultor_id" class="block text-sm font-medium text-gray-700 mb-1">Consultor</label>
            <select id="consultor_id" name="consultor_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                <option value="">Todos</option>
                @foreach($consultores as $consultor)
                    <option value="{{ $consultor->id }}" {{ $filtros['consultor_id'] == $consultor->id ? 'selected' : '' }}>
                        {{ $consultor->primer_nombre }} {{ $consultor->primer_apellido }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Quincena -->
        <div>
            <label for="quincena" class="block text-sm font-medium text-gray-700 mb-1">Quincena</label>
            <select id="quincena" name="quincena" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                <option value="">Todas</option>
                @foreach($quincenas as $valor => $nombre)
                    <option value="{{ $valor }}" {{ $filtros['quincena'] == $valor ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Fecha Inicio -->
        <div>
            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ $filtros['fecha_inicio'] }}" 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
        </div>
        
        <!-- Fecha Fin -->
        <div>
            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="{{ $filtros['fecha_fin'] }}" 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
        </div>
        
        <!-- Botón de Filtrar -->
        <div class="flex items-end md:col-span-3 justify-center mt-4">
            <button type="submit" style="background-color: #0047AB; color: white; padding: 12px 32px; border-radius: 6px; font-weight: bold; font-size: 18px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <svg style="width: 24px; height: 24px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span>APLICAR FILTROS</span>
            </button>
        </div>
    </form>
</div>

<!-- Tabla de Pagos -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-medium text-[#000000]">Pagos</h2>
            <p class="text-sm text-gray-500 mt-1">
                @if($filtros['consultor_id'])
                    Mostrando pagos de {{ $consultores->where('id', $filtros['consultor_id'])->first()->primer_nombre ?? '' }} 
                    {{ $consultores->where('id', $filtros['consultor_id'])->first()->primer_apellido ?? '' }}
                @else
                    Mostrando todos los pagos
                @endif
                
                @if($filtros['estado'])
                    con estado: <span class="font-medium">{{ ucfirst($filtros['estado']) }}</span>
                @endif
            </p>
        </div>
        
        <div>
            <a href="{{ route('admin.pagos.generar.form') }}" style="background-color: #0047AB; color: white; padding: 8px 16px; border-radius: 6px; font-weight: bold; font-size: 14px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>GENERAR PAGOS</span>
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quincena</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultor</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horas Totales</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Neto (USD)</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Neto (Bs)</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pagos as $pago)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $pago->periodo ? $pago->periodo : ($pago->fecha_inicio ? $pago->fecha_inicio->format('d/m/Y') : 'N/A') }}
                            @if($pago->fecha_fin)
                                <br><span class="text-xs text-gray-500">al {{ $pago->fecha_fin->format('d/m/Y') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600">
                                        {{ substr($pago->consultor->primer_nombre, 0, 1) }}{{ substr($pago->consultor->primer_apellido, 0, 1) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $pago->consultor->primer_nombre }} {{ $pago->consultor->primer_apellido }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $pago->consultor->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @if($pago->detalles->count() > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $pago->detalles->count() }} {{ Str::plural('empresa', $pago->detalles->count()) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Sin empresas</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $pago->detalles->sum('horas') }}
                            @if($pago->detalles->count() > 1)
                                <span class="text-xs text-gray-500">
                                    ({{ $pago->detalles->count() }} empresas)
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($pago->monto_total, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Bs. {{ number_format($pago->monto_total_bs, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pago->estado == 'pendiente')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            @elseif($pago->estado == 'pagado')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Pagado
                                </span>
                            @elseif($pago->estado == 'anulado')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Anulado
                                </span>
                            @elseif($pago->estado == 'confirmado')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Confirmado
                                </span>
                            @endif
                            
                            @if($pago->detalles->count() > 0)
                                <div class="mt-1 text-xs text-gray-500">
                                    {{ $pago->tipo_moneda }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.pagos.show', $pago->id) }}" 
                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-[#4682B4] bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Ver
                                </a>
                                
                                @if($pago->estado == 'pendiente')
                                    <button onclick="mostrarModalPagar('{{ $pago->id }}', '{{ $pago->consultor->primer_nombre }} {{ $pago->consultor->primer_apellido }}', '{{ number_format($pago->monto_total, 2, ',', '.') }}')" 
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Pagar
                                    </button>
                                    
                                    <form action="{{ route('admin.pagos.anular', $pago->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de anular este pago?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Anular
                                        </button>
                                    </form>
                                @endif
                                
                                @if($pago->estado == 'pagado' || $pago->estado == 'confirmado')
                                    <a href="{{ route('admin.pagos.comprobante', $pago->id) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Comprobante
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
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

<!-- Modal Pagar -->
<div id="modalPagar" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Pago</h3>
        
        <form id="formPagar" action="" method="POST">
            @csrf
            <div class="mb-4">
                <label for="fecha_pago" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Pago *</label>
                <input type="date" id="fecha_pago" name="fecha_pago" required
                       value="{{ date('Y-m-d') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
            </div>
            
            <div class="mb-4">
                <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="cerrarModalPagar()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit" style="background-color: #0047AB; color: white; padding: 8px 16px; border-radius: 6px; font-weight: bold; font-size: 14px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>CONFIRMAR PAGO</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Anular -->
<div id="modalAnular" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Anulación</h3>
        
        <form id="formAnular" action="" method="POST">
            @csrf
            <div class="mb-4">
                <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo de Anulación *</label>
                <textarea id="motivo" name="motivo" rows="3" required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="cerrarModalAnular()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit" style="background-color: #DC2626; color: white; padding: 8px 16px; border-radius: 6px; font-weight: bold; font-size: 14px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>ANULAR PAGO</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Funciones para modales
    function mostrarModalPagar(id) {
        var baseUrl = "{{ url('admin/pagos') }}";
        document.getElementById('formPagar').action = baseUrl + "/" + id + "/pagar";
        document.getElementById('modalPagar').classList.remove('hidden');
    }
    
    function cerrarModalPagar() {
        document.getElementById('modalPagar').classList.add('hidden');
        document.getElementById('observaciones').value = '';
    }
    
    function mostrarModalAnular(id) {
        var baseUrl = "{{ url('admin/pagos') }}";
        document.getElementById('formAnular').action = baseUrl + "/" + id + "/anular";
        document.getElementById('modalAnular').classList.remove('hidden');
    }
    
    function cerrarModalAnular() {
        document.getElementById('modalAnular').classList.add('hidden');
        document.getElementById('motivo').value = '';
    }
</script>
@endsection