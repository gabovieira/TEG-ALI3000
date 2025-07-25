@extends('layouts.admin')

@section('title', 'Crear Usuario - ALI3000')

@section('page-title', 'Crear Usuario')

@section('admin-content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Crear Nuevo Usuario</h1>
            <p class="text-gray-600">Ingrese los datos del nuevo usuario</p>
        </div>
        <a href="{{ route('admin.usuarios.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
        </a>
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

    <!-- Formulario de creación -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Información básica -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Personal</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-600">*</span></label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1">Apellido <span class="text-red-600">*</span></label>
                            <input type="text" name="apellido" id="apellido" value="{{ old('apellido') }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-600">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
                
                <!-- Información de cuenta -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Cuenta</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="tipo_usuario" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Usuario <span class="text-red-600">*</span></label>
                            <select name="tipo_usuario" id="tipo_usuario" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="consultor" {{ old('tipo_usuario') == 'consultor' ? 'selected' : '' }}>Consultor</option>
                                <option value="administrador" {{ old('tipo_usuario') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-600">*</span></label>
                            <select name="estado" id="estado" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="pendiente_registro" {{ old('estado') == 'pendiente_registro' ? 'selected' : '' }}>Pendiente de Registro</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-red-600">*</span></label>
                            <input type="password" name="password" id="password" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Asignación de empresas (solo para consultores) -->
            <div id="empresas-section" class="border-t border-gray-200 pt-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Asignación de Empresas</h3>
                
                @if($empresas->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($empresas as $empresa)
                            <div class="flex items-center">
                                <input type="checkbox" name="empresas[]" id="empresa-{{ $empresa->id }}" value="{{ $empresa->id }}"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ in_array($empresa->id, old('empresas', [])) ? 'checked' : '' }}>
                                <label for="empresa-{{ $empresa->id }}" class="ml-2 block text-sm text-gray-900">
                                    {{ $empresa->nombre }}
                                    <span class="text-xs text-gray-500 block">{{ $empresa->rif }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        No hay empresas disponibles para asignar
                    </div>
                @endif
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
    
    <script>
        // Mostrar/ocultar sección de empresas según el tipo de usuario
        document.addEventListener('DOMContentLoaded', function() {
            const tipoUsuarioSelect = document.getElementById('tipo_usuario');
            const empresasSection = document.getElementById('empresas-section');
            
            function toggleEmpresasSection() {
                if (tipoUsuarioSelect.value === 'consultor') {
                    empresasSection.style.display = 'block';
                } else {
                    empresasSection.style.display = 'none';
                }
            }
            
            // Ejecutar al cargar la página
            toggleEmpresasSection();
            
            // Ejecutar cuando cambie el tipo de usuario
            tipoUsuarioSelect.addEventListener('change', toggleEmpresasSection);
        });
    </script>
@endsection