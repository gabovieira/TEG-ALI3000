@extends('layouts.admin')

@section('title', 'Agregar Cuenta Bancaria - ALI3000')

@section('page-title', 'Agregar Cuenta Bancaria')

@section('admin-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Agregar Cuenta Bancaria</h1>
        <p class="text-[#708090]">Consultor: {{ $consultor->primer_nombre }} {{ $consultor->primer_apellido }}</p>
    </div>
    
    <div>
        <a href="{{ route('admin.datos-bancarios.index', $consultor->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Volver a la lista
        </a>
    </div>
</div>

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Formulario de Creación -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Datos de la Cuenta Bancaria</h2>
    
    <form action="{{ route('admin.datos-bancarios.store', $consultor->id) }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="banco" class="block text-sm font-medium text-gray-700 mb-1">Banco <span class="text-red-500">*</span></label>
                <input type="text" id="banco" name="banco" value="{{ old('banco') }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                @error('banco')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="tipo_cuenta" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cuenta <span class="text-red-500">*</span></label>
                <select id="tipo_cuenta" name="tipo_cuenta" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                    <option value="ahorro" {{ old('tipo_cuenta') == 'ahorro' ? 'selected' : '' }}>Ahorro</option>
                    <option value="corriente" {{ old('tipo_cuenta') == 'corriente' ? 'selected' : '' }}>Corriente</option>
                </select>
                @error('tipo_cuenta')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="numero_cuenta" class="block text-sm font-medium text-gray-700 mb-1">Número de Cuenta <span class="text-red-500">*</span></label>
                <input type="text" id="numero_cuenta" name="numero_cuenta" value="{{ old('numero_cuenta') }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                <p id="validacionCuenta" class="mt-1 text-sm text-gray-500"></p>
                @error('numero_cuenta')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="cedula_rif" class="block text-sm font-medium text-gray-700 mb-1">Cédula/RIF <span class="text-red-500">*</span></label>
                <input type="text" id="cedula_rif" name="cedula_rif" value="{{ old('cedula_rif') }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                @error('cedula_rif')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="titular" class="block text-sm font-medium text-gray-700 mb-1">Titular <span class="text-red-500">*</span></label>
                <input type="text" id="titular" name="titular" value="{{ old('titular') }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                @error('titular')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <div class="flex items-center h-full">
                    <input type="checkbox" id="es_principal" name="es_principal" class="h-4 w-4 text-[#4682B4] focus:ring-[#4682B4] border-gray-300 rounded" {{ old('es_principal') ? 'checked' : '' }}>
                    <label for="es_principal" class="ml-2 block text-sm text-gray-900">
                        Establecer como cuenta principal
                    </label>
                </div>
                @error('es_principal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" style="background-color: #0047AB; color: white; padding: 12px 32px; border-radius: 6px; font-weight: bold; font-size: 18px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <svg style="width: 24px; height: 24px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>GUARDAR</span>
            </button>
        </div>
    </form>
</div>

<script>
    // Validación de número de cuenta
    document.getElementById('banco').addEventListener('change', validarCuenta);
    document.getElementById('numero_cuenta').addEventListener('input', validarCuenta);
    
    function validarCuenta() {
        const banco = document.getElementById('banco').value;
        const numeroCuenta = document.getElementById('numero_cuenta').value;
        
        if (banco && numeroCuenta) {
            fetch("{{ route('admin.datos-bancarios.validar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    banco: banco,
                    numero_cuenta: numeroCuenta
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const validacionElement = document.getElementById('validacionCuenta');
                    
                    if (data.valido) {
                        validacionElement.className = 'mt-1 text-sm text-green-600';
                    } else {
                        validacionElement.className = 'mt-1 text-sm text-red-600';
                    }
                    
                    validacionElement.textContent = data.mensaje;
                }
            })
            .catch(error => {
                console.error('Error al validar cuenta:', error);
            });
        }
    }
</script>
@endsection