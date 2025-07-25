@extends('layouts.consultor')

@section('title', 'Mis Datos Bancarios - ALI3000')

@section('page-title', 'Mis Datos Bancarios')

@section('consultor-content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-[#000000]">Mis Datos Bancarios</h1>
            <p class="text-[#708090]">Gestiona tus cuentas bancarias para recibir pagos</p>
        </div>
        <a href="{{ route('consultor.datos-bancarios.create') }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold flex items-center hover:bg-blue-700 transition-colors shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
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

@if($datosBancarios->isEmpty())
    <!-- Estado vacío -->
    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100 text-center">
        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes datos bancarios registrados</h3>
        <p class="text-gray-600 mb-6">Agrega al menos una cuenta bancaria para poder recibir tus pagos.</p>
        <a href="{{ route('consultor.datos-bancarios.create') }}" 
           class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold inline-flex items-center hover:bg-blue-700 transition-colors shadow-lg text-lg">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Agregar Primera Cuenta
        </a>
    </div>
@else
    <!-- Lista de cuentas bancarias -->
    <div class="grid gap-6">
        @foreach($datosBancarios as $cuenta)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $cuenta->banco }}</h3>
                                        <p class="text-sm text-gray-600">{{ $cuenta->tipo_cuenta === 'ahorro' ? 'Cuenta de Ahorro' : 'Cuenta Corriente' }}</p>
                                    </div>
                                </div>
                                @if($cuenta->es_principal)
                                    <span class="ml-3 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        Principal
                                    </span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Número de Cuenta:</span>
                                    <p class="font-medium text-gray-900">{{ $cuenta->numero_cuenta }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Titular:</span>
                                    <p class="font-medium text-gray-900">{{ $cuenta->titular }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Cédula/RIF:</span>
                                    <p class="font-medium text-gray-900">{{ $cuenta->cedula_rif }}</p>
                                </div>

                            </div>
                            
                            @if($cuenta->observaciones)
                                <div class="mt-4 p-3 bg-gray-50 rounded-md">
                                    <span class="text-gray-500 text-sm">Observaciones:</span>
                                    <p class="text-gray-700 text-sm mt-1">{{ $cuenta->observaciones }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Acciones -->
                        <div class="flex flex-col space-y-2 ml-4">
                            @if(!$cuenta->es_principal)
                                <form action="{{ route('consultor.datos-bancarios.principal', $cuenta->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded-md hover:bg-green-200 transition-colors">
                                        Hacer Principal
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('consultor.datos-bancarios.edit', $cuenta->id) }}" 
                               class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-md hover:bg-blue-200 transition-colors text-center">
                                Editar
                            </a>
                            
                            @if($datosBancarios->count() > 1)
                                <form action="{{ route('consultor.datos-bancarios.destroy', $cuenta->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cuenta bancaria?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-sm bg-red-100 text-red-700 px-3 py-1 rounded-md hover:bg-red-200 transition-colors">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-3 text-xs text-gray-500">
                    Registrada el {{ $cuenta->created_at->format('d/m/Y') }} a las {{ $cuenta->created_at->format('H:i') }}
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Información importante -->
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Información Importante</h3>
            <div class="mt-2 text-sm text-blue-700 space-y-1">
                <p>• La cuenta marcada como "Principal" será utilizada por defecto para los pagos.</p>
                <p>• Puedes tener múltiples cuentas bancarias registradas.</p>
                <p>• Asegúrate de que los datos sean correctos para evitar problemas con los pagos.</p>
                <p>• Los datos bancarios son confidenciales y solo visibles para ti y los administradores.</p>
            </div>
        </div>
    </div>
</div>
@endsection