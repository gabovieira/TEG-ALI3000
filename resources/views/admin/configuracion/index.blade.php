@extends('layouts.admin')

@section('title', 'Configuración del Sistema - ALI3000')

@section('page-title', 'Configuración del Sistema')

@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#000000]">Configuración del Sistema</h1>
    <p class="text-[#708090]">Gestiona los parámetros de configuración del sistema</p>
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

@if(session('info'))
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
        {{ session('info') }}
    </div>
@endif

<!-- Filtros por Categoría -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Filtrar por Categoría</h2>
    
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.configuracion.index') }}" 
           style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; background-color: {{ $categoria === 'all' ? '#0047AB' : '#f3f4f6' }}; color: {{ $categoria === 'all' ? 'white' : '#374151' }}; padding: 8px 16px; border-radius: 6px; font-weight: bold; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            Todas
        </a>
        
        @foreach($categorias as $cat)
            <a href="{{ route('admin.configuracion.index', ['categoria' => $cat]) }}" 
               style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; background-color: {{ $categoria === $cat ? '#0047AB' : '#f3f4f6' }}; color: {{ $categoria === $cat ? 'white' : '#374151' }}; padding: 8px 16px; border-radius: 6px; font-weight: bold; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                {{ ucfirst($cat) }}
            </a>
        @endforeach
    </div>
</div>

<!-- Configuraciones por Categoría -->
@foreach($configuraciones as $nombreCategoria => $configs)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium text-[#000000]">{{ ucfirst($nombreCategoria) }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Configuraciones de {{ strtolower($nombreCategoria) }}
                    </p>
                </div>
                
                <button type="button" 
                        onclick="guardarCategoria('{{ $nombreCategoria }}')"
                        style="background-color: #0047AB; color: white; padding: 12px 32px; border-radius: 6px; font-weight: bold; font-size: 18px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <svg style="width: 24px; height: 24px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>GUARDAR CAMBIOS</span>
                </button>
            </div>
        </div>
        
        <form id="form-{{ $nombreCategoria }}" action="{{ route('admin.configuracion.update-categoria') }}" method="POST">
            @csrf
            <input type="hidden" name="categoria" value="{{ $nombreCategoria }}">
            
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6">
                    @foreach($configs as $config)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <!-- Información de la configuración -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $config->clave }}
                                    </label>
                                    @if($config->descripcion)
                                        <p class="text-xs text-gray-500">{{ $config->descripcion }}</p>
                                    @endif
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($config->tipo) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Campo de valor -->
                                <div>
                                    @if($config->tipo === 'booleano')
                                        <select name="configuraciones[{{ $config->id }}][valor]" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                                            <option value="1" {{ $config->valor == '1' ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ $config->valor == '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    @elseif($config->tipo === 'numero')
                                        <input type="number" 
                                               name="configuraciones[{{ $config->id }}][valor]" 
                                               value="{{ $config->valor }}"
                                               step="0.01"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                                    @else
                                        <input type="text" 
                                               name="configuraciones[{{ $config->id }}][valor]" 
                                               value="{{ $config->valor }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                                    @endif
                                </div>
                                
                                <!-- Información de actualización -->
                                <div class="text-sm text-gray-500">
                                    @if($config->fecha_actualizacion)
                                        <p>Actualizado: {{ $config->fecha_actualizacion->format('d/m/Y H:i') }}</p>
                                    @endif
                                    @if($config->actualizadoPor)
                                        <p>Por: {{ $config->actualizadoPor->primer_nombre }} {{ $config->actualizadoPor->primer_apellido }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
@endforeach

@if($configuraciones->isEmpty())
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-[#708090]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-[#000000]">No hay configuraciones</h3>
            <p class="mt-1 text-sm text-[#708090]">
                @if($categoria !== 'all')
                    No se encontraron configuraciones para la categoría "{{ $categoria }}"
                @else
                    No hay configuraciones disponibles en el sistema
                @endif
            </p>
        </div>
    </div>
@endif

<script>
function guardarCategoria(categoria) {
    const form = document.getElementById('form-' + categoria);
    if (form) {
        // Mostrar confirmación
        if (confirm('¿Estás seguro de que deseas guardar los cambios en la categoría "' + categoria + '"?')) {
            form.submit();
        }
    }
}

// Confirmar antes de salir si hay cambios sin guardar
let formChanged = false;

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            formChanged = true;
        });
    });
});

window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Resetear flag cuando se envía el formulario
document.addEventListener('submit', function() {
    formChanged = false;
});
</script>
@endsection