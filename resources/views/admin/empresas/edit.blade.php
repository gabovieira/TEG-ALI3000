@extends('layouts.admin')

@section('title', 'Editar Empresa - ALI3000')

@section('page-title', 'Editar Empresa')

@section('admin-content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Editar Empresa</h1>
            <p class="text-gray-600">Modifique los datos de la empresa</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.empresas.show', $empresa->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Mensajes de error -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Por favor corrija los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

    <!-- Formulario de edición -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <form action="{{ route('admin.empresas.update', $empresa->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Información básica -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de la Empresa</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Empresa <span class="text-red-600">*</span></label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $empresa->nombre) }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label for="rif" class="block text-sm font-medium text-gray-700 mb-1">RIF <span class="text-red-600">*</span></label>
                            <input type="text" name="rif" id="rif" value="{{ old('rif', $empresa->rif) }}" required
                                   placeholder="J-12345678-9"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label for="tipo_empresa" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Empresa <span class="text-red-600">*</span></label>
                            <select name="tipo_empresa" id="tipo_empresa" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Seleccionar tipo</option>
                                <option value="S.A." {{ old('tipo_empresa', $empresa->tipo_empresa) == 'S.A.' ? 'selected' : '' }}>S.A.</option>
                                <option value="C.A." {{ old('tipo_empresa', $empresa->tipo_empresa) == 'C.A.' ? 'selected' : '' }}>C.A.</option>
                                <option value="Otro" {{ old('tipo_empresa', $empresa->tipo_empresa) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-600">*</span></label>
                            <select name="estado" id="estado" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="activa" {{ old('estado', $empresa->estado) == 'activa' ? 'selected' : '' }}>Activa</option>
                                <option value="inactiva" {{ old('estado', $empresa->estado) == 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Información de contacto -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Contacto</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <textarea name="direccion" id="direccion" rows="3"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('direccion', $empresa->direccion) }}</textarea>
                        </div>
                        
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $empresa->telefono) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $empresa->email) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Asignación de consultores -->
            <div class="border-t border-gray-200 pt-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Asignación de Consultores</h3>
                
                @if($consultores->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($consultores as $consultor)
                            <div class="flex items-center">
                                <input type="checkbox" name="consultores[]" id="consultor-{{ $consultor->id }}" value="{{ $consultor->id }}"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ in_array($consultor->id, old('consultores', $consultoresAsignados)) ? 'checked' : '' }}>
                                <label for="consultor-{{ $consultor->id }}" class="ml-2 block text-sm text-gray-900">
                                    {{ $consultor->primer_nombre }} {{ $consultor->primer_apellido }}
                                    <span class="text-xs text-gray-500 block">{{ $consultor->email }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        No hay consultores disponibles para asignar
                    </div>
                @endif
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection