@extends('layouts.admin')

@section('title', 'Detalles de Usuario - ALI3000')

@section('page-title', 'Detalles de Usuario')

@section('admin-content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detalles de Usuario</h1>
            <p class="text-gray-600">Información completa del usuario</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.usuarios.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
        </div>
    </div>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Usuario -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center mb-6">
                    <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-900">{{ $usuario->primer_nombre }} {{ $usuario->primer_apellido }}</h2>
                        <div class="flex items-center mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $usuario->tipo_usuario == 'administrador' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($usuario->tipo_usuario) }}
                            </span>
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $usuario->estado == 'activo' ? 'bg-green-100 text-green-800' : 
                                   ($usuario->estado == 'inactivo' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $usuario->estado)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre completo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $usuario->primer_nombre }} {{ $usuario->primer_apellido }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $usuario->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $usuario->telefono ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de registro</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $usuario->fecha_creacion ? $usuario->fecha_creacion->format('d/m/Y H:i') : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo de usuario</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($usuario->tipo_usuario) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $usuario->estado)) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-6 flex space-x-3">
                    <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Editar Usuario
                    </a>
                    <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de que desea eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                            Eliminar Usuario
                        </button>
                    </form>
                </div>
            </div>

            @if($usuario->tipo_usuario == 'consultor')
                <!-- Empresas Asignadas (solo para consultores) -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Empresas Asignadas</h3>
                        <a href="{{ route('admin.usuarios.asignar-empresas', $usuario->id) }}" class="px-3 py-1 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Gestionar Empresas
                        </a>
                    </div>

                    @if($usuario->empresas->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($usuario->empresas as $empresa)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $empresa->nombre }}</h4>
                                            <p class="text-sm text-gray-600">{{ $empresa->rif }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $empresa->tipo_empresa }}</p>
                                        </div>
                                        <form action="{{ route('admin.usuarios.eliminar-asignacion', ['usuarioId' => $usuario->id, 'empresaId' => $empresa->id]) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar esta asignación?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar asignación">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500">
                            No hay empresas asignadas a este consultor
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Estadísticas y Acciones Rápidas -->
        <div>
            @if($usuario->tipo_usuario == 'consultor')
                <!-- Estadísticas para consultores -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Horas Aprobadas</span>
                                <span class="font-medium">{{ $estadisticas['total_horas'] ?? 0 }} hrs</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($estadisticas['total_horas'] ?? 0) / 2) }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Pagos Recibidos</span>
                                <span class="font-medium">${{ number_format($estadisticas['total_pagos'] ?? 0, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, ($estadisticas['total_pagos'] ?? 0) / 10) }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Horas Pendientes</span>
                                <span class="font-medium">{{ $estadisticas['horas_pendientes'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ min(100, ($estadisticas['horas_pendientes'] ?? 0) * 10) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Editar Usuario</span>
                        </div>
                    </a>
                    
                    @if($usuario->tipo_usuario == 'consultor')
                        <a href="{{ route('admin.usuarios.asignar-empresas', $usuario->id) }}" class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Gestionar Empresas</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.horas.index', ['usuario_id' => $usuario->id]) }}" class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Ver Horas Registradas</span>
                            </div>
                        </a>
                    @endif
                    
                    <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-between p-3 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Eliminar Usuario</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection