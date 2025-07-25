@extends('layouts.admin')

@section('title', 'Datos Bancarios - ALI3000')

@section('page-title', 'Datos Bancarios')

@section('admin-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Datos Bancarios de {{ $consultor->primer_nombre }} {{ $consultor->primer_apellido }}</h1>
        <p class="text-[#708090]">Gestiona las cuentas bancarias del consultor</p>
    </div>
    
    <div>
        <a href="{{ route('admin.usuarios.show', $consultor->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Volver al Perfil
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

<!-- Lista de Cuentas Bancarias -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-medium text-[#000000]">Cuentas Bancarias</h2>
        
        <a href="{{ route('admin.datos-bancarios.create', $consultor->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#4682B4] hover:bg-[#36648B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Agregar Cuenta
        </a>
    </div>
    
    @if($datosBancarios->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($datosBancarios as $cuenta)
                <div class="bg-gray-50 rounded-lg p-4 border {{ $cuenta->es_principal ? 'border-blue-300' : 'border-gray-200' }} relative">
                    @if($cuenta->es_principal)
                        <div class="absolute top-2 right-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                            Principal
                        </div>
                    @endif
                    
                    <h3 class="text-md font-medium text-gray-800 mb-2">{{ $cuenta->banco }}</h3>
                    <p><span class="font-medium">Tipo de Cuenta:</span> {{ $cuenta->tipo_cuenta === 'ahorro' ? 'Ahorro' : 'Corriente' }}</p>
                    <p><span class="font-medium">Número de Cuenta:</span> {{ $cuenta->numero_cuenta }}</p>
                    <p><span class="font-medium">Cédula/RIF:</span> {{ $cuenta->cedula_rif }}</p>
                    <p><span class="font-medium">Titular:</span> {{ $cuenta->titular }}</p>
                    
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('admin.datos-bancarios.edit', [$consultor->id, $cuenta->id]) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
                            <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Editar
                        </a>
                        
                        @if(!$cuenta->es_principal)
                            <form action="{{ route('admin.datos-bancarios.principal', [$consultor->id, $cuenta->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1 border border-blue-300 text-xs leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Principal
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.datos-bancarios.destroy', [$consultor->id, $cuenta->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cuenta bancaria?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-red-300 text-xs leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">No hay cuentas bancarias registradas</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>El consultor no tiene cuentas bancarias registradas.</p>
                        <p class="mt-1">Haz clic en "Agregar Cuenta" para registrar una cuenta bancaria.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Información Importante -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Información Importante</h2>
    
    <div class="space-y-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Cuenta Principal</h3>
                <p class="mt-1 text-sm text-gray-600">
                    La cuenta marcada como principal será utilizada por defecto para los pagos.
                </p>
            </div>
        </div>
        
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Datos Correctos</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Asegúrate de que los datos bancarios sean correctos para evitar problemas con los pagos.
                </p>
            </div>
        </div>
        
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Eliminación de Cuentas</h3>
                <p class="mt-1 text-sm text-gray-600">
                    No podrás eliminar cuentas bancarias que estén asociadas a pagos existentes.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection