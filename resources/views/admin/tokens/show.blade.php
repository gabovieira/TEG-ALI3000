@extends('layouts.admin')

@section('title', 'Detalles del Token - ALI3000')

@section('page-title', 'Detalles del Token')

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Detalles del Token</h1>
        <p class="text-[#708090] mt-1">Información completa del token y usuario pre-registrado</p>
    </div>
    <a href="{{ route('admin.tokens.index') }}" 
       class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver
    </a>
</div>

<!-- Estado del Token -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-[#000000]">Estado del Token</h2>
            @if($token->isUsado())
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                    Usado
                </span>
            @elseif($token->isExpirado())
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                    Expirado
                </span>
            @else
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                    Válido
                </span>
            @endif
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-[#708090]">Token</p>
                <p class="font-mono text-xs bg-gray-100 p-2 rounded overflow-x-auto whitespace-nowrap">{{ $token->token }}</p>
            </div>
            <div>
                <p class="text-sm text-[#708090]">Creado</p>
                <p class="font-medium">{{ $token->fecha_creacion ? $token->fecha_creacion->format('d/m/Y H:i') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-[#708090]">Expira</p>
                <p class="font-medium">{{ $token->fecha_expiracion->format('d/m/Y H:i') }}</p>
                @if($token->isValido())
                    <p class="text-xs text-green-600">{{ $token->dias_para_expirar }} días restantes</p>
                @endif
            </div>
        </div>
        
        @if($token->isValido())
            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800 font-medium">URL de Registro:</p>
                <a href="{{ $token->url_registro }}" target="_blank" 
                   class="text-blue-600 hover:underline text-sm break-all">
                    {{ $token->url_registro }}
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Información del Usuario -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Datos Personales -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-[#000000]">Datos Personales</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-[#708090]">Nombre Completo</p>
                    <p class="font-medium text-[#000000]">{{ $token->usuario->nombre_completo }}</p>
                </div>
                <div>
                    <p class="text-sm text-[#708090]">Estado</p>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        @if($token->usuario->estado == 'activo') bg-green-100 text-green-800
                        @elseif($token->usuario->estado == 'pendiente_registro') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $token->usuario->estado)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentos de Identidad -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-[#000000]">Documentos de Identidad</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($token->usuario->documentos as $documento)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-[#000000]">
                                {{ ucfirst($documento->tipo_documento) }}
                                @if($documento->isPrincipal())
                                    <span class="text-xs text-blue-600">(Principal)</span>
                                @endif
                            </p>
                            <p class="text-sm text-[#708090]">{{ $documento->numero_formateado }}</p>
                        </div>
                    </div>
                @endforeach
                
                @if($token->usuario->documentos->isEmpty())
                    <p class="text-[#708090] text-center py-4">No hay documentos registrados</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Datos Laborales -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-[#000000]">Datos Laborales</h3>
        </div>
        <div class="p-6">
            @if($token->usuario->datosLaborales)
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-[#708090]">Tarifa por Hora</p>
                        <p class="font-medium text-[#000000]">${{ number_format($token->usuario->datosLaborales->tarifa_por_hora, 2) }} USD</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#708090]">Nivel de Desarrollo</p>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst(str_replace('-', ' ', $token->usuario->datosLaborales->nivel_desarrollo)) }}
                        </span>
                    </div>
                </div>
            @else
                <p class="text-[#708090] text-center py-4">No hay datos laborales registrados</p>
            @endif
        </div>
    </div>

    <!-- Información de Contacto -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-[#000000]">Información de Contacto</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($token->usuario->contactos as $contacto)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-[#000000]">
                                {{ ucfirst($contacto->tipo_contacto) }}
                                @if($contacto->isPrincipal())
                                    <span class="text-xs text-blue-600">(Principal)</span>
                                @endif
                            </p>
                            <p class="text-sm text-[#708090]">{{ $contacto->valor }}</p>
                        </div>
                    </div>
                @endforeach
                
                @if($token->usuario->contactos->isEmpty())
                    <p class="text-[#708090] text-center py-4">
                        Sin información de contacto
                        <br>
                        <span class="text-xs">Se completará durante el registro final</span>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Acciones -->
@if($token->isValido())
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-[#000000] mb-4">Acciones</h3>
            <div class="flex flex-wrap gap-3">
                <button onclick="reenviarToken({{ $token->id }})" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    Reenviar Token
                </button>
                <button onclick="extenderToken({{ $token->id }})" 
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                    Extender Expiración
                </button>
                <button onclick="invalidarToken({{ $token->id }})" 
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Invalidar Token
                </button>
            </div>
        </div>
    </div>
@endif

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
</script>
@endsection