@extends('layouts.consultor')

@section('title', 'Registrar Horas - ALI3000')

@section('consultor-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#000000]">Registrar Horas</h1>
    <p class="text-[#708090]">Registra las horas trabajadas por empresa</p>
</div>

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    @if($empresas->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-[#000000]">No tienes empresas asignadas</h3>
            <p class="mt-1 text-sm text-[#708090]">Contacta a un administrador para que te asigne a una o más empresas.</p>
            <div class="mt-6">
                <a href="{{ route('consultor.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#4682B4] hover:bg-blue-600">
                    Volver al Dashboard
                </a>
            </div>
        </div>
    @else
        <form action="{{ route('consultor.horas.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Empresa -->
                <div>
                    <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-1">Empresa *</label>
                    <select id="empresa_id" name="empresa_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" required>
                        <option value="">Selecciona una empresa</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('empresa_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
            <!-- Fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                <input type="date" id="fecha" name="fecha" value="{{ old('fecha', $fechaActual) }}" 
                       max="{{ date('Y-m-d') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" required>
                @error('fecha')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">No puedes registrar horas para fechas futuras</p>
            </div>
        </div>
        
        <div class="mb-6">
            <!-- Horas Trabajadas -->
            <label for="horas_trabajadas" class="block text-sm font-medium text-gray-700 mb-1">Horas Trabajadas *</label>
            <div class="flex items-center">
                <input type="number" id="horas_trabajadas" name="horas_trabajadas" value="{{ old('horas_trabajadas') }}" 
                       min="0.5" max="12" step="0.5"
                       class="w-24 rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" required>
                <span class="ml-2 text-gray-500">horas</span>
                
                <div class="ml-6 flex space-x-2">
                    @foreach([0.5, 1, 2, 4, 8] as $horaPreset)
                        <button type="button" 
                                onclick="document.getElementById('horas_trabajadas').value = '{{ $horaPreset }}'"
                                class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">
                            {{ $horaPreset }}h
                        </button>
                    @endforeach
                </div>
            </div>
            @error('horas_trabajadas')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Mínimo 0.5 horas, máximo 12 horas por día</p>
        </div>
        
        <div class="mb-6">
            <!-- Descripción de Actividades -->
            <label for="descripcion_actividades" class="block text-sm font-medium text-gray-700 mb-1">Descripción de Actividades *</label>
            <textarea id="descripcion_actividades" name="descripcion_actividades" rows="4"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" required>{{ old('descripcion_actividades') }}</textarea>
            @error('descripcion_actividades')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Describe brevemente las actividades realizadas</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Información Importante:</h3>
            <ul class="text-xs text-gray-600 space-y-1 list-disc list-inside">
                <li>Las horas registradas quedarán en estado "pendiente" hasta que sean aprobadas por un administrador</li>
                <li>No puedes registrar más de 12 horas en total por día</li>
                <li>Solo puedes registrar horas para empresas a las que estás asignado</li>
                <li>Puedes editar o eliminar registros mientras estén en estado "pendiente"</li>
            </ul>
        </div>
        
        <div class="flex justify-end space-x-3">
            <a href="{{ route('consultor.horas.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-[#4682B4] text-white rounded-md hover:bg-blue-600">
                Registrar Horas
            </button>
        </div>
        </form>
    @endif
</div>

<script>
    // Validación adicional en el cliente
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const horasInput = document.getElementById('horas_trabajadas');
        
        form.addEventListener('submit', function(e) {
            const horas = parseFloat(horasInput.value);
            
            if (isNaN(horas) || horas < 0.5 || horas > 12) {
                e.preventDefault();
                alert('Las horas trabajadas deben estar entre 0.5 y 12');
                return false;
            }
            
            // Validar que sea múltiplo de 0.5
            if (horas * 10 % 5 !== 0) {
                e.preventDefault();
                alert('Las horas deben ser múltiplos de 0.5 (ej: 1, 1.5, 2, 2.5, etc)');
                return false;
            }
        });
    });
</script>
@endsection