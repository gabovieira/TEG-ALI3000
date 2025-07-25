@extends('layouts.consultor')

@section('title', 'Mis Horas Registradas - ALI3000')

@section('consultor-content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Mis Horas Registradas</h1>
        <p class="text-[#708090]">Gestiona tus registros de horas trabajadas</p>
    </div>
    <a href="{{ route('consultor.horas.create') }}" class="px-4 py-2 bg-[#4682B4] text-white rounded-md hover:bg-blue-600 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Registrar Horas
    </a>
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
    
    <form action="{{ route('consultor.horas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
        
        <div class="md:col-span-4 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-[#4682B4] text-white rounded-md hover:bg-blue-600">
                Aplicar Filtros
            </button>
        </div>
    </form>
</div>

<!-- Tabla de Registros -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-medium text-[#000000]">Registros de Horas</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $registro->fecha->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $registro->empresa->nombre }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                            <a href="{{ route('consultor.horas.show', $registro->id) }}" class="text-[#4682B4] hover:text-blue-800 mr-3">
                                Ver
                            </a>
                            
                            @if($registro->puedeEditar())
                                <a href="{{ route('consultor.horas.edit', $registro->id) }}" class="text-[#4682B4] hover:text-blue-800 mr-3">
                                    Editar
                                </a>
                                
                                <form action="{{ route('consultor.horas.destroy', $registro->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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
@endsection