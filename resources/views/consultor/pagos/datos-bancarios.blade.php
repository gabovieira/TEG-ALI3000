@extends('layouts.consultor')

@section('title', 'Mis Datos Bancarios - ALI3000')

@section('page-title', 'Mis Datos Bancarios')

@section('consultor-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Mis Datos Bancarios</h1>
        <p class="text-[#708090]">Gestiona tus cuentas bancarias para recibir pagos</p>
    </div>
    
    <div>
        <a href="{{ route('consultor.datos-bancarios.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#4682B4] hover:bg-[#36648B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Agregar Cuenta
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

@if($datosBancarios->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-[#000000]">Cuentas Bancarias Registradas</h2>
        </div>
        
        <div class="divide-y divide-gray-200">
            @foreach($datosBancarios as $cuenta)
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <h3 class="text-lg font-medium text-gray-900">{{ $cuenta->banco }}</h3>
                                @if($cuenta->es_principal)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Principal
                                    </span>
                                @endif
                            </div>
                            
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Tipo de Cuenta</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $cuenta->tipo_cuenta === 'ahorro' ? 'Ahorro' : 'Corriente' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Número de Cuenta</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $cuenta->numero_cuenta }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Cédula/RIF</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $cuenta->cedula_rif }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Titular</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $cuenta->titular }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="ml-6 flex flex-col space-y-2">
                            @if(!$cuenta->es_principal)
                                <form action="{{ route('consultor.datos-bancarios.principal', $cuenta->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Marcar como Principal
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('consultor.datos-bancarios.edit', $cuenta->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
                                Editar
                            </a>
                            
                            <form action="{{ route('consultor.datos-bancarios.destroy', $cuenta->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cuenta bancaria?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes cuentas bancarias registradas</h3>
        <p class="mt-1 text-sm text-gray-500">Agrega una cuenta bancaria para recibir tus pagos.</p>
        <div class="mt-6">
            <a href="{{ route('consultor.datos-bancarios.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#4682B4] hover:bg-[#36648B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Agregar Primera Cuenta
            </a>
        </div>
    </div>
@endif

<div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Información Importante</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc pl-5 space-y-1">
                    <li>La cuenta marcada como "Principal" será la que se use por defecto para los pagos.</li>
                    <li>Puedes tener múltiples cuentas registradas pero solo una puede ser principal.</li>
                    <li>Asegúrate de que los datos sean correctos para evitar problemas con los pagos.</li>
                    <li>No puedes eliminar una cuenta que tenga pagos asociados.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection