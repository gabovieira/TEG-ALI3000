@extends('layouts.admin')

@section('title', 'Detalle de Registro de Horas - ALI3000')

@section('page-title', 'Detalle de Registro')

@section('admin-content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-[#000000]">Detalle de Registro de Horas</h1>
            <p class="text-[#708090]">Información completa del registro de horas</p>
        </div>
        <a href="{{ route('admin.horas.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
        </a>
    </div>
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

<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Información del Registro -->
        <div>
            <h2 class="text-lg font-medium text-[#000000] mb-4">Información del Registro</h2>
            
            <div class="space-y-4">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">ID:</span>
                    <span class="font-medium">{{ $registro->id }}</span>
                </div>
                
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Fecha:</span>
                    <span class="font-medium">{{ $registro->fecha->format('d/m/Y') }}</span>
                </div>
                
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Horas Trabajadas:</span>
                    <span class="font-medium">{{ $registro->horas_trabajadas }}</span>
                </div>
                
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Estado:</span>
                    <span class="font-medium">
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
                    </span>
                </div>
                
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Tipo de Registro:</span>
                    <span class="font-medium">{{ $registro->tipo_registro == 'manual' ? 'Manual' : 'Automático' }}</span>
                </div>
                
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Fecha de Creación:</span>
                    <span class="font-medium">{{ $registro->fecha_creacion ? $registro->fecha_creacion->format('d/m/Y H:i') : 'N/A' }}</span>
                </div>
                
                @if($registro->estado != 'pendiente')
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-600">Fecha de Aprobación/Rechazo:</span>
                        <span class="font-medium">{{ $registro->fecha_aprobacion ? $registro->fecha_aprobacion->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-600">Aprobado/Rechazado por:</span>
                        <span class="font-medium">{{ $registro->aprobador ? $registro->aprobador->primer_nombre . ' ' . $registro->aprobador->primer_apellido : 'N/A' }}</span>
                    </div>
                @endif
                
                @if($registro->estado == 'rechazado')
                    <div class="border-b border-gray-100 pb-2">
                        <span class="text-gray-600 block mb-1">Motivo del Rechazo:</span>
                        <span class="font-medium block bg-red-50 p-2 rounded">{{ $registro->motivo_rechazo }}</span>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Información del Consultor y Empresa -->
        <div>
            <h2 class="text-lg font-medium text-[#000000] mb-4">Consultor y Empresa</h2>
            
            <div class="space-y-4">
                <div class="border-b border-gray-100 pb-2">
                    <span class="text-gray-600 block">Consultor:</span>
                    <div class="flex items-center mt-1">
                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 mr-2">
                            {{ substr($registro->usuario->primer_nombre, 0, 1) }}{{ substr($registro->usuario->primer_apellido, 0, 1) }}
                        </div>
                        <span class="font-medium">{{ $registro->usuario->primer_nombre }} {{ $registro->usuario->primer_apellido }}</span>
                    </div>
                </div>
                
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ $registro->usuario->email }}</span>
                </div>
                
                <div class="border-b border-gray-100 pb-2">
                    <span class="text-gray-600 block">Empresa:</span>
                    <span class="font-medium block mt-1">{{ $registro->empresa->nombre }}</span>
                </div>
                
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-600">RIF:</span>
                    <span class="font-medium">{{ $registro->empresa->rif }}</span>
                </div>
            </div>
            
            <h2 class="text-lg font-medium text-[#000000] mt-6 mb-4">Descripción de Actividades</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-800">{{ $registro->descripcion_actividades }}</p>
            </div>
        </div>
    </div>
    
    @if($registro->estado == 'pendiente')
        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end space-x-4">
            <button type="button" 
                    onclick="mostrarModalRechazar({{ $registro->id }})" 
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                Rechazar
            </button>
            
            <button type="button" 
                    onclick="mostrarModalAprobar({{ $registro->id }})" 
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Aprobar
            </button>
        </div>
    @endif
</div>

<!-- Modal Aprobar -->
<div id="modalAprobar" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Aprobación</h3>
        <p class="text-gray-700 mb-6">¿Estás seguro de que deseas aprobar este registro de horas?</p>
        
        <form id="formAprobar" action="{{ url('admin/horas/' . $registro->id . '/aprobar') }}" method="POST" class="flex justify-end space-x-3">
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
        
        <form id="formRechazar" action="{{ url('admin/horas/' . $registro->id . '/rechazar') }}" method="POST">
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

<script>
    function mostrarModalAprobar() {
        // La acción ya está configurada en el formulario
        document.getElementById('modalAprobar').classList.remove('hidden');
    }
    
    function cerrarModalAprobar() {
        document.getElementById('modalAprobar').classList.add('hidden');
    }
    
    function mostrarModalRechazar() {
        // La acción ya está configurada en el formulario
        document.getElementById('modalRechazar').classList.remove('hidden');
    }
    
    function cerrarModalRechazar() {
        document.getElementById('modalRechazar').classList.add('hidden');
        document.getElementById('motivo_rechazo').value = '';
    }
</script>
@endsection