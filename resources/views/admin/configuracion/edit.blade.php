@extends('layouts.admin')

@section('title', 'Editar Configuración - ALI3000')

@section('page-title', 'Editar Configuración')

@section('admin-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#000000]">Editar Configuración</h1>
            <p class="text-[#708090]">Modifica los valores de configuración del sistema</p>
        </div>
        
        <a href="{{ route('admin.configuracion.index') }}" 
           style="display: inline-flex !important; visibility: visible !important; opacity: 1 !important; background-color: #f3f4f6; color: #374151; padding: 8px 16px; border-radius: 6px; font-weight: bold; font-size: 14px; align-items: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>VOLVER</span>
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

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-medium text-[#000000]">{{ $configuracion->clave }}</h2>
        <p class="text-sm text-gray-500 mt-1">Categoría: {{ ucfirst($configuracion->categoria) }}</p>
    </div>
    
    <form action="{{ route('admin.configuracion.update', $configuracion->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información de la configuración -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Clave</label>
                    <input type="text" 
                           value="{{ $configuracion->clave }}" 
                           disabled
                           class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <input type="text" 
                           value="{{ ucfirst($configuracion->tipo) }}" 
                           disabled
                           class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <input type="text" 
                           value="{{ ucfirst($configuracion->categoria) }}" 
                           disabled
                           class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                </div>
                
                @if($configuracion->fecha_actualizacion)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Última Actualización</label>
                        <input type="text" 
                               value="{{ $configuracion->fecha_actualizacion->format('d/m/Y H:i:s') }}" 
                               disabled
                               class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                    </div>
                @endif
                
                @if($configuracion->actualizadoPor)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Actualizado Por</label>
                        <input type="text" 
                               value="{{ $configuracion->actualizadoPor->primer_nombre }} {{ $configuracion->actualizadoPor->primer_apellido }}" 
                               disabled
                               class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                    </div>
                @endif
            </div>
            
            <!-- Campos editables -->
            <div class="space-y-4">
                <div>
                    <label for="valor" class="block text-sm font-medium text-gray-700 mb-1">
                        Valor <span class="text-red-500">*</span>
                    </label>
                    
                    @if($configuracion->tipo === 'booleano')
                        <select id="valor" 
                                name="valor" 
                                required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                            <option value="1" {{ $configuracion->valor == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ $configuracion->valor == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    @elseif($configuracion->tipo === 'numero')
                        <input type="number" 
                               id="valor"
                               name="valor" 
                               value="{{ $configuracion->valor }}"
                               step="0.01"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                    @else
                        <input type="text" 
                               id="valor"
                               name="valor" 
                               value="{{ $configuracion->valor }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                    @endif
                    
                    @error('valor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">{{ $configuracion->descripcion }}</textarea>
                    
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Información adicional según el tipo -->
                @if($configuracion->tipo === 'numero')
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-blue-700">
                                <p class="font-medium">Valor numérico</p>
                                <p>Puedes usar decimales (ej: 16.00, 3.50)</p>
                            </div>
                        </div>
                    </div>
                @elseif($configuracion->tipo === 'booleano')
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-blue-700">
                                <p class="font-medium">Valor booleano</p>
                                <p>Selecciona "Sí" para activar o "No" para desactivar</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.configuracion.index') }}" 
               style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; background-color: #f3f4f6; color: #374151; padding: 12px 32px; border-radius: 6px; font-weight: bold; font-size: 18px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <svg style="width: 24px; height: 24px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span>CANCELAR</span>
            </a>
            
            <button type="submit" style="background-color: #0047AB; color: white; padding: 12px 32px; border-radius: 6px; font-weight: bold; font-size: 18px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <svg style="width: 24px; height: 24px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>GUARDAR CAMBIOS</span>
            </button>
        </div>
    </form>
</div>
@endsection