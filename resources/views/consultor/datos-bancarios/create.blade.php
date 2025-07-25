@extends('layouts.consultor')

@section('title', 'Agregar Datos Bancarios - ALI3000')

@section('page-title', 'Agregar Datos Bancarios')

@section('consultor-content')
<div class="mb-6">
    <div class="flex items-center">
        <a href="{{ route('consultor.datos-bancarios.index') }}" 
           class="text-gray-500 hover:text-gray-700 mr-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-[#000000]">Agregar Datos Bancarios</h1>
            <p class="text-[#708090]">Registra una nueva cuenta bancaria para recibir pagos</p>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <form action="{{ route('consultor.datos-bancarios.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Información Bancaria -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Banco -->
            <div>
                <label for="banco" class="block text-sm font-medium text-gray-700 mb-1">
                    Banco <span class="text-red-500">*</span>
                </label>
                <select id="banco" name="banco" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" 
                        required>
                    <option value="">Seleccionar banco</option>
                    @foreach($bancos as $banco)
                        <option value="{{ $banco }}" {{ old('banco') == $banco ? 'selected' : '' }}>
                            {{ $banco }}
                        </option>
                    @endforeach
                </select>
                @error('banco')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo de Cuenta -->
            <div>
                <label for="tipo_cuenta" class="block text-sm font-medium text-gray-700 mb-1">
                    Tipo de Cuenta <span class="text-red-500">*</span>
                </label>
                <select id="tipo_cuenta" name="tipo_cuenta" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" 
                        required>
                    <option value="">Seleccionar tipo</option>
                    @foreach($tiposCuenta as $valor => $nombre)
                        <option value="{{ $valor }}" {{ old('tipo_cuenta') == $valor ? 'selected' : '' }}>
                            {{ $nombre }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_cuenta')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Número de Cuenta -->
            <div>
                <label for="numero_cuenta" class="block text-sm font-medium text-gray-700 mb-1">
                    Número de Cuenta <span class="text-red-500">*</span>
                </label>
                <input type="text" id="numero_cuenta" name="numero_cuenta" 
                       value="{{ old('numero_cuenta') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" 
                       placeholder="Ej: 01020123456789012345"
                       required>
                @error('numero_cuenta')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Ingresa el número completo de la cuenta (20 dígitos para cuentas bancarias)</p>
            </div>

            <!-- Cédula/RIF -->
            <div>
                <label for="cedula_rif" class="block text-sm font-medium text-gray-700 mb-1">
                    Cédula/RIF del Titular <span class="text-red-500">*</span>
                </label>
                <input type="text" id="cedula_rif" name="cedula_rif" 
                       value="{{ old('cedula_rif') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" 
                       placeholder="Ej: V-12345678 o J-123456789"
                       required>
                @error('cedula_rif')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Titular -->
            <div class="md:col-span-2">
                <label for="titular" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre del Titular <span class="text-red-500">*</span>
                </label>
                <input type="text" id="titular" name="titular" 
                       value="{{ old('titular') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" 
                       placeholder="Nombre completo del titular de la cuenta"
                       required>
                @error('titular')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Observaciones -->
        <div>
            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">
                Observaciones
            </label>
            <textarea id="observaciones" name="observaciones" rows="3"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" 
                      placeholder="Información adicional sobre esta cuenta (opcional)">{{ old('observaciones') }}</textarea>
            @error('observaciones')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4 pt-6 border-t">
            <a href="{{ route('consultor.datos-bancarios.index') }}" 
               class="bg-gray-100 text-gray-700 px-6 py-2 rounded-md font-medium hover:bg-gray-200 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-[#0047AB] text-white px-6 py-2 rounded-md font-medium hover:bg-[#003d96] transition-colors">
                Guardar Datos Bancarios
            </button>
        </div>
    </form>
</div>

<!-- Información de ayuda -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Consejos para Registrar tus Datos Bancarios</h3>
            <div class="mt-2 text-sm text-blue-700 space-y-1">
                <p>• Verifica que todos los datos sean correctos antes de guardar</p>
                <p>• El número de cuenta debe incluir todos los dígitos (generalmente 20 para bancos venezolanos)</p>
                <p>• Si es tu primera cuenta, se marcará automáticamente como principal</p>
                <p>• Solo se aceptan cuentas de ahorro y corriente para transferencias bancarias</p>
                <p>• Los pagos se realizarán únicamente por transferencia bancaria</p>
            </div>
        </div>
    </div>
</div>
@endsection