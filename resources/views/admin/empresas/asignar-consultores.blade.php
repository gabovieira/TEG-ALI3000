@extends('layouts.admin')

@section('title', 'Asignar Consultores - ALI3000')

@section('page-title', 'Asignar Consultores')

@section('admin-content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Asignar Consultores</h1>
            <p class="text-gray-600">Gestione los consultores asignados a {{ $empresa->nombre }}</p>
        </div>
        <a href="{{ route('admin.empresas.show', $empresa->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
        </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Consultores Asignados -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Consultores Asignados</h3>
            
            @if($consultoresAsignados->count() > 0)
                <div class="space-y-3">
                    @foreach($consultoresAsignados as $consultor)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $consultor->primer_nombre }} {{ $consultor->primer_apellido }}</h4>
                                <p class="text-sm text-gray-600">{{ $consultor->email }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @php
                                        $pivot = $consultor->pivot;
                                        $fechaAsignacion = $pivot ? \Carbon\Carbon::parse($pivot->fecha_asignacion)->format('d/m/Y') : 'N/A';
                                    @endphp
                                    Asignado: {{ $fechaAsignacion }}
                                </p>
                            </div>
                            <form action="{{ route('admin.empresas.eliminar-asignacion', ['empresaId' => $empresa->id, 'usuarioId' => $consultor->id]) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar esta asignación?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs rounded-full hover:bg-red-700">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    No hay consultores asignados a esta empresa
                </div>
            @endif
        </div>

        <!-- Consultores Disponibles -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Consultores Disponibles</h3>
            
            @if($consultoresDisponibles->count() > 0)
                <form action="{{ route('admin.empresas.guardar-asignaciones', $empresa->id) }}" method="POST">
                    @csrf
                    
                    <!-- Búsqueda -->
                    <div class="mb-4">
                        <input type="text" id="buscar-consultor" placeholder="Buscar consultor..." 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <div class="space-y-3 max-h-96 overflow-y-auto" id="lista-consultores">
                        @foreach($consultoresDisponibles as $consultor)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg consultor-item">
                                <input type="checkbox" name="consultores[]" id="consultor-{{ $consultor->id }}" value="{{ $consultor->id }}"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="consultor-{{ $consultor->id }}" class="ml-2 block flex-grow">
                                    <span class="font-medium text-gray-900 block">{{ $consultor->primer_nombre }} {{ $consultor->primer_apellido }}</span>
                                    <span class="text-sm text-gray-600 block">{{ $consultor->email }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Guardar Asignaciones
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-4 text-gray-500">
                    No hay consultores disponibles para asignar
                </div>
            @endif
        </div>
    </div>
    
    <script>
        // Búsqueda de consultores
        document.addEventListener('DOMContentLoaded', function() {
            const buscarInput = document.getElementById('buscar-consultor');
            const consultorItems = document.querySelectorAll('.consultor-item');
            
            buscarInput.addEventListener('input', function() {
                const busqueda = this.value.toLowerCase();
                
                consultorItems.forEach(function(item) {
                    const texto = item.textContent.toLowerCase();
                    if (texto.includes(busqueda)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection