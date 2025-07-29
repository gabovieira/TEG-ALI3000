@extends('layouts.consultor')

@section('title', 'Mis Empresas - ALI3000')

@section('consultor-content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Mis Empresas</h1>
    @if($empresas->isEmpty())
        <div class="bg-yellow-50 text-yellow-700 p-4 rounded">No tienes empresas asignadas actualmente.</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded shadow-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">Nombre</th>
                        <th class="px-4 py-2 border-b">RIF</th>
                        <th class="px-4 py-2 border-b">Tipo</th>
                        <th class="px-4 py-2 border-b">Dirección</th>
                        <th class="px-4 py-2 border-b">Teléfono</th>
                        <th class="px-4 py-2 border-b">Email</th>
                        <th class="px-4 py-2 border-b">Estado</th>
                        <th class="px-4 py-2 border-b">Tipo de Asignación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $empresa)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $empresa->nombre }}</td>
                            <td class="px-4 py-2 border-b">{{ $empresa->rif }}</td>
                            <td class="px-4 py-2 border-b">{{ $empresa->tipo_empresa }}</td>
                            <td class="px-4 py-2 border-b">{{ $empresa->direccion }}</td>
                            <td class="px-4 py-2 border-b">{{ $empresa->telefono }}</td>
                            <td class="px-4 py-2 border-b">{{ $empresa->email }}</td>
                            <td class="px-4 py-2 border-b">{{ ucfirst($empresa->estado) }}</td>
                            <td class="px-4 py-2 border-b">
                                @php
                                    $asignacion = auth()->user()->getAsignacionEmpresa($empresa->id);
                                    $tooltipText = '';
                                    $icon = '';
                                    $bgColor = '';
                                    $textColor = '';
                                    $label = '';

                                    if ($asignacion) {
                                        switch($asignacion->tipo_asignacion) {
                                            case 'tiempo_completo':
                                                $icon = 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z';
                                                $bgColor = 'bg-green-100';
                                                $textColor = 'text-green-800';
                                                $label = 'Tiempo Completo';
                                                $tooltipText = 'Asignación de tiempo completo (40 horas semanales)';
                                                break;
                                            case 'parcial':
                                                $icon = 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z';
                                                $bgColor = 'bg-blue-100';
                                                $textColor = 'text-blue-800';
                                                $label = 'Tiempo Parcial';
                                                $tooltipText = 'Asignación de tiempo parcial (menos de 40 horas semanales)';
                                                break;
                                            case 'temporal':
                                                $icon = 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z';
                                                $bgColor = 'bg-yellow-100';
                                                $textColor = 'text-yellow-800';
                                                $label = 'Temporal';
                                                $tooltipText = 'Asignación temporal (por proyecto o período específico)';
                                                break;
                                        }
                                    }
                                @endphp
                                @if($asignacion)
                                    <div class="group relative inline-block">
                                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $bgColor }} {{ $textColor }} cursor-help">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                                            </svg>
                                            {{ $label }}
                                        </div>
                                        <div class="absolute z-10 hidden group-hover:block w-64 px-3 py-2 mt-1 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg shadow-lg">
                                            <p class="font-medium text-gray-900">{{ $label }}</p>
                                            <p class="text-gray-600">{{ $tooltipText }}</p>
                                            @if($asignacion->fecha_asignacion)
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Asignado el: {{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">No especificado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
