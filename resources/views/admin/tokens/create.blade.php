@extends('layouts.admin')

@section('title', 'Crear Token de Registro - ALI3000')

@section('page-title', 'Invitar Usuario')

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Invitar Nuevo Usuario</h1>
        <p class="text-[#708090] mt-1">Genera una invitación para que un nuevo consultor complete su registro</p>
    </div>
    <a href="{{ route('admin.tokens.index') }}" 
       class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver
    </a>
</div>

<!-- Mensajes de error -->
@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
@endif

<!-- Formulario -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-[#000000]">Información del Consultor</h2>
        <p class="text-sm text-[#708090] mt-1">Completa los datos básicos del consultor que se registrará</p>
    </div>
    
    <form action="{{ route('admin.tokens.store') }}" method="POST" class="p-6">
        @csrf
        
        <!-- Información Personal -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-[#000000] mb-4 border-b border-gray-200 pb-2">Información Personal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Primer Nombre -->
                <div>
                    <label for="primer_nombre" class="block text-sm font-medium text-[#000000] mb-2">
                        Primer Nombre *
                    </label>
                    <input type="text" 
                           id="primer_nombre" 
                           name="primer_nombre" 
                           value="{{ old('primer_nombre') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent"
                           required>
                    @error('primer_nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Primer Apellido -->
                <div>
                    <label for="primer_apellido" class="block text-sm font-medium text-[#000000] mb-2">
                        Primer Apellido *
                    </label>
                    <input type="text" 
                           id="primer_apellido" 
                           name="primer_apellido" 
                           value="{{ old('primer_apellido') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent"
                           required>
                    @error('primer_apellido')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Documentos de Identidad -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-[#000000] mb-4 border-b border-gray-200 pb-2">Documentos de Identidad</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cédula -->
                <div>
                    <label for="cedula" class="block text-sm font-medium text-[#000000] mb-2">
                        Cédula de Identidad *
                    </label>
                    <div class="flex">
                        <select name="cedula_tipo" class="px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent">
                            <option value="V" {{ old('cedula_tipo', 'V') == 'V' ? 'selected' : '' }}>V</option>
                            <option value="E" {{ old('cedula_tipo') == 'E' ? 'selected' : '' }}>E</option>
                        </select>
                        <input type="text" 
                               id="cedula" 
                               name="cedula" 
                               value="{{ old('cedula') }}"
                               placeholder="12345678"
                               class="flex-1 px-3 py-2 border-t border-b border-r border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent"
                               required>
                    </div>
                    <p class="text-xs text-[#708090] mt-1">Solo números, sin puntos ni guiones</p>
                    @error('cedula')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RIF -->
                <div>
                    <label for="rif" class="block text-sm font-medium text-[#000000] mb-2">
                        RIF (Opcional)
                    </label>
                    <div class="flex">
                        <select name="rif_tipo" class="px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent">
                            <option value="V" {{ old('rif_tipo', 'V') == 'V' ? 'selected' : '' }}>V</option>
                            <option value="E" {{ old('rif_tipo') == 'E' ? 'selected' : '' }}>E</option>
                            <option value="J" {{ old('rif_tipo') == 'J' ? 'selected' : '' }}>J</option>
                            <option value="P" {{ old('rif_tipo') == 'P' ? 'selected' : '' }}>P</option>
                            <option value="G" {{ old('rif_tipo') == 'G' ? 'selected' : '' }}>G</option>
                        </select>
                        <input type="text" 
                               id="rif" 
                               name="rif" 
                               value="{{ old('rif') }}"
                               placeholder="123456789"
                               class="flex-1 px-3 py-2 border-t border-b border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent">
                        <input type="text" 
                               name="rif_dv" 
                               value="{{ old('rif_dv') }}"
                               placeholder="0"
                               maxlength="1"
                               class="w-12 px-2 py-2 border-t border-b border-r border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent">
                    </div>
                    <p class="text-xs text-[#708090] mt-1">Formato: J-123456789-0 (dígito verificador opcional)</p>
                    @error('rif')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Datos Laborales -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-[#000000] mb-4 border-b border-gray-200 pb-2">Datos Laborales</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tarifa por Hora -->
                <div>
                    <label for="tarifa_por_hora" class="block text-sm font-medium text-[#000000] mb-2">
                        Tarifa por Hora (USD) *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" 
                               id="tarifa_por_hora" 
                               name="tarifa_por_hora" 
                               value="{{ old('tarifa_por_hora') }}"
                               step="0.01"
                               min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent"
                               required>
                    </div>
                    @error('tarifa_por_hora')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nivel de Desarrollo -->
                <div>
                    <label for="nivel_desarrollo" class="block text-sm font-medium text-[#000000] mb-2">
                        Nivel de Desarrollo *
                    </label>
                    <select id="nivel_desarrollo" 
                            name="nivel_desarrollo" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent"
                            required>
                        <option value="">Seleccionar nivel</option>
                        <option value="junior" {{ old('nivel_desarrollo') == 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="semi-senior" {{ old('nivel_desarrollo') == 'semi-senior' ? 'selected' : '' }}>Semi-Senior</option>
                        <option value="senior" {{ old('nivel_desarrollo') == 'senior' ? 'selected' : '' }}>Senior</option>
                    </select>
                    @error('nivel_desarrollo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Configuración del Token -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-[#000000] mb-4 border-b border-gray-200 pb-2">Configuración del Token</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Días de Expiración -->
                <div>
                    <label for="dias_expiracion" class="block text-sm font-medium text-[#000000] mb-2">
                        Días para Expirar *
                    </label>
                    <select id="dias_expiracion" 
                            name="dias_expiracion" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent">
                        <option value="7" {{ old('dias_expiracion', 7) == 7 ? 'selected' : '' }}>7 días</option>
                        <option value="14" {{ old('dias_expiracion') == 14 ? 'selected' : '' }}>14 días</option>
                        <option value="21" {{ old('dias_expiracion') == 21 ? 'selected' : '' }}>21 días</option>
                        <option value="30" {{ old('dias_expiracion') == 30 ? 'selected' : '' }}>30 días</option>
                    </select>
                    @error('dias_expiracion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email de Notificación -->
                <div>
                    <label for="email_notificacion" class="block text-sm font-medium text-[#000000] mb-2">
                        Email para Notificación
                    </label>
                    <input type="email" 
                           id="email_notificacion" 
                           name="email_notificacion" 
                           value="{{ old('email_notificacion') }}"
                           placeholder="email@ejemplo.com"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4682B4] focus:border-transparent">
                    <p class="text-xs text-[#708090] mt-1">Email donde se enviará el token (opcional)</p>
                    @error('email_notificacion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Enviar Email -->
            <div class="mt-4">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="enviar_email" 
                           name="enviar_email" 
                           value="1"
                           {{ old('enviar_email', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-[#4682B4] bg-gray-100 border-gray-300 rounded focus:ring-[#4682B4] focus:ring-2">
                    <label for="enviar_email" class="ml-2 text-sm font-medium text-[#000000]">
                        Enviar token por email automáticamente
                    </label>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Información Importante:</h3>
            <ul class="text-xs text-blue-700 space-y-1">
                <li>• Se pre-registrará el consultor con los datos laborales y documentos proporcionados</li>
                <li>• El consultor recibirá un email con el enlace para completar su registro</li>
                <li>• En el registro final, el consultor solo deberá agregar: email personal, teléfono y contraseña</li>
                <li>• El token expirará automáticamente después del tiempo especificado</li>
                <li>• Una vez usado el token, no podrá ser reutilizado</li>
            </ul>
        </div>



        <!-- Botones -->
        <div class="flex justify-end space-x-4 mt-6 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.tokens.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-[#4682B4] text-white rounded-lg hover:bg-[#FF6347] transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                Enviar Invitación
            </button>
        </div>
        
        <!-- BOTÓN DE ENVÍO SIMPLE -->
        <div style="text-align: center; margin: 30px 0; padding: 20px; background: #f0f0f0;">
            <button type="submit" style="background: #4682B4; color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                ENVIAR INVITACIÓN
            </button>
        </div>
        
    </form>
</div>
@endsection