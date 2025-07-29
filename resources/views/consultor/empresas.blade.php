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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
