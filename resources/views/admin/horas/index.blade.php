@extends('layouts.admin')

@section('title', 'Gestión de Horas - ALI3000')

@section('page-title', 'Gestión de Horas')

@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#000000]">Gestión de Horas</h1>
    <p class="text-[#708090]">Administra y aprueba las horas registradas por los consultores</p>
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

<!-- Filtros -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Filtros</h2>
    
    <form action="{{ route('admin.horas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Estado -->
        <div>
            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select id="estado" name="estado" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                <option value="">Todos</option>
                <option value="pendiente" {{ $filtros['estado'] == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="aprobado" {{ $filtros['estado'] == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                <option value="rechazado" {{ $filtros['estado'] == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
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
        
        <!-- Empresa -->
        <div>
            <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
            <select id="empresa_id" name="empresa_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                <option value="">Todas</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}" {{ $filtros['empresa_id'] == $empresa->id ? 'selected' : '' }}>
                        {{ $empresa->nombre }}
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

<!-- Tabla de Registros Detallados -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-medium text-[#000000]">Registros de Horas</h2>
            <p class="text-sm text-gray-500 mt-1">
                @if($filtros['consultor_id'])
                    Mostrando registros de {{ $consultores->where('id', $filtros['consultor_id'])->first()->primer_nombre ?? '' }} 
                    {{ $consultores->where('id', $filtros['consultor_id'])->first()->primer_apellido ?? '' }}
                @else
                    Mostrando todos los registros
                @endif
                
                @if($filtros['estado'])
                    con estado: <span class="font-medium">{{ ucfirst($filtros['estado']) }}</span>
                @endif
            </p>
        </div>
        
        <div class="flex space-x-2">
            <button id="btnAprobarMultiple" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                Aprobar Seleccionados
            </button>
            <button id="btnRechazarMultiple" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                Rechazar Seleccionados
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-[#4682B4] focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultor</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($registros as $registro)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($registro->estado == 'pendiente')
                                <input type="checkbox" name="registros[]" value="{{ $registro->id }}" class="registro-checkbox rounded border-gray-300 text-[#4682B4] focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $registro->fecha->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>
                                {{ $registro->usuario->primer_nombre }} {{ $registro->usuario->primer_apellido }}
                                <div class="text-xs text-gray-500">
                                    {{ $registro->usuario->datosLaborales->nivel_desarrollo ?? 'No definido' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $registro->empresa->nombre }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                            {{ $registro->horas_trabajadas }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($registro->estado == 'pendiente')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            @elseif($registro->estado == 'aprobado')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aprobado
                                </span>
                            @elseif($registro->estado == 'rechazado')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rechazado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                            {{ $registro->descripcion_actividades }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ url('admin/horas/' . $registro->id) }}" class="text-[#4682B4] hover:text-blue-800 mr-3">
                                Ver
                            </a>
                            
                            @if($registro->estado == 'pendiente')
                                <button type="button" 
                                        onclick="mostrarModalAprobar({{ $registro->id }})" 
                                        class="text-green-600 hover:text-green-800 mr-3">
                                    Aprobar
                                </button>
                                
                                <button type="button" 
                                        onclick="mostrarModalRechazar({{ $registro->id }})" 
                                        class="text-red-600 hover:text-red-800">
                                    Rechazar
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No hay registros de horas que coincidan con los filtros aplicados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $registros->withQueryString()->links() }}
    </div>
</div>

<!-- Modal Aprobar -->
<div id="modalAprobar" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Aprobación</h3>
        <p class="text-gray-700 mb-6">¿Estás seguro de que deseas aprobar este registro de horas?</p>
        
        <form id="formAprobar" action="" method="POST" class="flex justify-end space-x-3">
            @csrf
            <button type="button" onclick="cerrarModalAprobar()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancelar
            </button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Aprobar
            </button>
        </form>
    </div>
</div>

<!-- Modal Rechazar -->
<div id="modalRechazar" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Rechazo</h3>
        
        <form id="formRechazar" action="" method="POST">
            @csrf
            <div class="mb-4">
                <label for="motivo_rechazo" class="block text-sm font-medium text-gray-700 mb-1">Motivo del Rechazo *</label>
                <textarea id="motivo_rechazo" name="motivo_rechazo" rows="3" required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="cerrarModalRechazar()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Rechazar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Aprobar Múltiple -->
<div id="modalAprobarMultiple" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Aprobación Múltiple</h3>
        <p class="text-gray-700 mb-6">¿Estás seguro de que deseas aprobar los registros seleccionados?</p>
        
        <form id="formAprobarMultiple" action="{{ route('admin.horas.aprobar-multiple') }}" method="POST" class="flex justify-end space-x-3">
            @csrf
            <div id="registrosSeleccionadosAprobar"></div>
            
            <button type="button" onclick="cerrarModalAprobarMultiple()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancelar
            </button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Aprobar Todos
            </button>
        </form>
    </div>
</div>

<!-- Modal Rechazar Múltiple -->
<div id="modalRechazarMultiple" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Rechazo Múltiple</h3>
        
        <form id="formRechazarMultiple" action="{{ route('admin.horas.rechazar-multiple') }}" method="POST">
            @csrf
            <div id="registrosSeleccionadosRechazar"></div>
            
            <div class="mb-4">
                <label for="motivo_rechazo_multiple" class="block text-sm font-medium text-gray-700 mb-1">Motivo del Rechazo *</label>
                <textarea id="motivo_rechazo_multiple" name="motivo_rechazo" rows="3" required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="cerrarModalRechazarMultiple()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Rechazar Todos
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Funciones para modales individuales
    function mostrarModalAprobar(id) {
        // Usar una forma más segura de generar la URL
        var baseUrl = "{{ url('admin/horas') }}";
        document.getElementById('formAprobar').action = baseUrl + "/" + id + "/aprobar";
        document.getElementById('modalAprobar').classList.remove('hidden');
    }
    
    function cerrarModalAprobar() {
        document.getElementById('modalAprobar').classList.add('hidden');
    }
    
    function mostrarModalRechazar(id) {
        // Usar una forma más segura de generar la URL
        var baseUrl = "{{ url('admin/horas') }}";
        document.getElementById('formRechazar').action = baseUrl + "/" + id + "/rechazar";
        document.getElementById('modalRechazar').classList.remove('hidden');
    }
    
    function cerrarModalRechazar() {
        document.getElementById('modalRechazar').classList.add('hidden');
        document.getElementById('motivo_rechazo').value = '';
    }
    
    // Funciones para modales múltiples
    document.getElementById('btnAprobarMultiple').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.registro-checkbox:checked');
        if (checkboxes.length === 0) return;
        
        const container = document.getElementById('registrosSeleccionadosAprobar');
        container.innerHTML = '';
        
        checkboxes.forEach(function(checkbox) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'registros[]';
            input.value = checkbox.value;
            container.appendChild(input);
        });
        
        document.getElementById('modalAprobarMultiple').classList.remove('hidden');
    });
    
    function cerrarModalAprobarMultiple() {
        document.getElementById('modalAprobarMultiple').classList.add('hidden');
    }
    
    document.getElementById('btnRechazarMultiple').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.registro-checkbox:checked');
        if (checkboxes.length === 0) return;
        
        const container = document.getElementById('registrosSeleccionadosRechazar');
        container.innerHTML = '';
        
        checkboxes.forEach(function(checkbox) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'registros[]';
            input.value = checkbox.value;
            container.appendChild(input);
        });
        
        document.getElementById('modalRechazarMultiple').classList.remove('hidden');
    });
    
    function cerrarModalRechazarMultiple() {
        document.getElementById('modalRechazarMultiple').classList.add('hidden');
        document.getElementById('motivo_rechazo_multiple').value = '';
    }
    
    // Seleccionar todos los checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.registro-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        }, this);
        
        actualizarBotonesMultiples();
    });
    
    // Actualizar estado de botones múltiples
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.registro-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', actualizarBotonesMultiples);
        });
        
        actualizarBotonesMultiples();
    });
    
    function actualizarBotonesMultiples() {
        const checkboxes = document.querySelectorAll('.registro-checkbox:checked');
        const btnAprobar = document.getElementById('btnAprobarMultiple');
        const btnRechazar = document.getElementById('btnRechazarMultiple');
        
        if (checkboxes.length > 0) {
            btnAprobar.disabled = false;
            btnRechazar.disabled = false;
        } else {
            btnAprobar.disabled = true;
            btnRechazar.disabled = true;
        }
    }
</script>
@endsection