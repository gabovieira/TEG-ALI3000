@extends('layouts.consultor')

@section('title', 'Detalle de Registro - ALI3000')

@section('consultor-content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Detalle de Registro</h1>
        <p class="text-[#708090]">Información completa del registro de horas</p>
    </div>
    <a href="{{ route('consultor.horas.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Información General -->
        <div>
            <h2 class="text-lg font-medium text-[#000000] mb-4">Información General</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Empresa</p>
                    <p class="font-medium">{{ $registro->empresa->nombre }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Fecha</p>
                    <p class="font-medium">{{ $registro->fecha->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Horas Trabajadas</p>
                    <p class="font-medium">{{ $registro->horas_trabajadas }} horas</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Estado</p>
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
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Tipo de Registro</p>
                    <p class="font-medium">{{ ucfirst($registro->tipo_registro) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Información de Aprobación -->
        <div>
            <h2 class="text-lg font-medium text-[#000000] mb-4">Información de Aprobación</h2>
            
            <div class="space-y-4">
                @if($registro->estado == 'pendiente')
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-yellow-800">Este registro está pendiente de aprobación por un administrador.</p>
                    </div>
                @elseif($registro->estado == 'aprobado')
                    <div>
                        <p class="text-sm text-gray-500">Aprobado por</p>
                        <p class="font-medium">{{ $registro->aprobador->nombre_completo }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Fecha de Aprobación</p>
                        <p class="font-medium">{{ $registro->fecha_aprobacion->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-green-800">Este registro ha sido aprobado y será considerado para el cálculo de pagos.</p>
                    </div>
                @elseif($registro->estado == 'rechazado')
                    <div>
                        <p class="text-sm text-gray-500">Rechazado por</p>
                        <p class="font-medium">{{ $registro->aprobador->nombre_completo }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Fecha de Rechazo</p>
                        <p class="font-medium">{{ $registro->fecha_aprobacion->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Motivo del Rechazo</p>
                        <p class="font-medium text-red-600">{{ $registro->motivo_rechazo }}</p>
                    </div>
                    
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-red-800">Este registro ha sido rechazado y no será considerado para el cálculo de pagos.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Descripción de Actividades -->
    <div class="mt-8">
        <h2 class="text-lg font-medium text-[#000000] mb-4">Descripción de Actividades</h2>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="whitespace-pre-line">{{ $registro->descripcion_actividades }}</p>
        </div>
    </div>
    
    <!-- Acciones -->
    <div class="mt-8 flex justify-end space-x-3">
        <a href="{{ route('consultor.horas.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
            Volver a la Lista
        </a>
        
        @if($registro->puedeEditar())
            <a href="{{ route('consultor.horas.edit', $registro->id) }}" class="px-4 py-2 bg-[#4682B4] text-white rounded-md hover:bg-blue-600">
                Editar Registro
            </a>
            
            <form action="{{ route('consultor.horas.destroy', $registro->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                    Eliminar Registro
                </button>
            </form>
        @endif
    </div>
</div>
@endsection