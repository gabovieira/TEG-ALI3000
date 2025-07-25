@extends('layouts.admin')

@section('title', 'Gestión de Tokens de Registro - ALI3000')

@section('page-title', 'Tokens de Registro')

@section('admin-content')

<!-- Botón de Invitar Usuario - SIMPLE Y DIRECTO -->
<div class="mb-6">
    <a href="{{ route('admin.tokens.create') }}" 
       style="background-color: #4682B4; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-block; font-weight: bold;">
        + Invitar Usuario
    </a>
</div>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Registrar Usuarios</h1>
        <p class="text-[#708090] mt-1">Gestiona el registro de nuevos consultores mediante tokens de invitación</p>
    </div>
    <div>
        <form action="{{ route('admin.tokens.limpiar-expirados') }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors"
                    onclick="return confirm('¿Estás seguro de eliminar todos los tokens expirados?')">
                Limpiar Expirados
            </button>
        </form>
    </div>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Total Tokens</p>
                <p class="text-3xl font-bold text-[#000000]">{{ $estadisticas['total'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Tokens Válidos</p>
                <p class="text-3xl font-bold text-green-600">{{ $estadisticas['validos'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Tokens Usados</p>
                <p class="text-3xl font-bold text-blue-600">{{ $estadisticas['usados'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[#708090] text-sm font-medium">Tokens Expirados</p>
                <p class="text-3xl font-bold text-red-600">{{ $estadisticas['expirados'] }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Mensajes de éxito/error -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
        @if(session('token_url'))
            <div class="mt-2">
                <strong>URL del token:</strong> 
                <a href="{{ session('token_url') }}" target="_blank" class="text-blue-600 hover:underline">
                    {{ session('token_url') }}
                </a>
            </div>
        @endif
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
@endif

<!-- Tabla de Tokens -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-[#000000]">Lista de Tokens</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#708090] uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#708090] uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#708090] uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#708090] uppercase tracking-wider">Creado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#708090] uppercase tracking-wider">Expira</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#708090] uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tokens as $token)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-[#000000]">
                                {{ $token->usuario->nombre_completo }}
                            </div>
                            <div class="text-xs text-[#708090]">
                                @php
                                    $cedula = $token->usuario->documentos()->where('tipo_documento', 'cedula')->first();
                                    $datosLaborales = $token->usuario->datosLaborales;
                                @endphp
                                @if($cedula)
                                    {{ $cedula->numero_formateado }}
                                @endif
                                @if($datosLaborales)
                                    • ${{ $datosLaborales->tarifa_por_hora }}/hr
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $emailContacto = $token->usuario->contactos()->where('tipo_contacto', 'email')->first();
                            @endphp
                            @if($emailContacto)
                                <div class="text-sm text-[#708090]">{{ $emailContacto->valor }}</div>
                                <div class="text-xs text-green-600">Email de notificación</div>
                            @else
                                <div class="text-sm text-gray-400">Sin email de notificación</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($token->isUsado())
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Usado
                                </span>
                            @elseif($token->isExpirado())
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Expirado
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Válido
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#708090]">
                            {{ $token->fecha_creacion ? $token->fecha_creacion->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#708090]">
                            {{ $token->fecha_expiracion->format('d/m/Y H:i') }}
                            @if($token->isValido())
                                <div class="text-xs text-green-600">
                                    ({{ $token->dias_para_expirar }} días restantes)
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.tokens.show', $token) }}" 
                                   class="text-[#4682B4] hover:text-[#FF6347]">Ver</a>
                                
                                @if($token->isValido())
                                    <button onclick="reenviarToken({{ $token->id }})" 
                                            class="text-green-600 hover:text-green-800">Reenviar</button>
                                    <button onclick="extenderToken({{ $token->id }})" 
                                            class="text-yellow-600 hover:text-yellow-800">Extender</button>
                                    <button onclick="invalidarToken({{ $token->id }})" 
                                            class="text-red-600 hover:text-red-800">Invalidar</button>
                                @endif
                                
                                <button onclick="eliminarToken({{ $token->id }})" 
                                        class="text-red-600 hover:text-red-800">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-[#708090]">
                            No hay tokens de registro creados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tokens->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $tokens->links() }}
        </div>
    @endif
</div>

<!-- Modales y Scripts -->
<script>
function reenviarToken(tokenId) {
    if (confirm('¿Reenviar token por email?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/tokens/${tokenId}/reenviar`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

function extenderToken(tokenId) {
    const dias = prompt('¿Cuántos días extender?', '7');
    if (dias && !isNaN(dias) && dias > 0) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/tokens/${tokenId}/extender`;
        form.innerHTML = `
            @csrf
            @method('PATCH')
            <input type="hidden" name="dias" value="${dias}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function invalidarToken(tokenId) {
    if (confirm('¿Invalidar este token? Esta acción no se puede deshacer.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/tokens/${tokenId}/invalidar`;
        form.innerHTML = `
            @csrf
            @method('PATCH')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function eliminarToken(tokenId) {
    if (confirm('¿Eliminar este token y el usuario asociado? Esta acción no se puede deshacer.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/tokens/${tokenId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection