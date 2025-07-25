@extends('layouts.consultor')

@section('title', 'Confirmar Recepción de Pago - ALI3000')

@section('page-title', 'Confirmar Recepción de Pago')

@section('consultor-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Confirmar Recepción de Pago</h1>
        <p class="text-[#708090]">Confirma que has recibido el pago correctamente</p>
    </div>
    
    <div>
        <a href="{{ route('consultor.pagos.show', $pago->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Volver al detalle
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

<!-- Resumen del Pago -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Resumen del Pago</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-2">Información del Pago</h3>
            <p><span class="font-medium">Período:</span> {{ $pago->nombre_quincena }}</p>
            <p><span class="font-medium">Horas:</span> {{ $pago->total_horas }}</p>
            <p><span class="font-medium">Monto Total:</span> ${{ number_format($pago->monto_neto, 2) }} USD</p>
            <p><span class="font-medium">Equivalente:</span> Bs {{ number_format($pago->monto_neto * $pago->tasa_cambio, 2) }}</p>
            @if($pago->fecha_pago)
                <p><span class="font-medium">Fecha de Pago:</span> {{ $pago->fecha_pago->format('d/m/Y') }}</p>
            @endif
            @if($pago->referencia_bancaria)
                <p><span class="font-medium">Referencia:</span> {{ $pago->referencia_bancaria }}</p>
            @endif
        </div>
        
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-2">Datos Bancarios Utilizados</h3>
            @if($pago->datosBancarios)
                <p><span class="font-medium">Banco:</span> {{ $pago->datosBancarios->banco }}</p>
                <p><span class="font-medium">Cuenta:</span> {{ $pago->datosBancarios->numero_cuenta }}</p>
                <p><span class="font-medium">Titular:</span> {{ $pago->datosBancarios->titular }}</p>
            @else
                <p class="text-gray-500">No se especificaron datos bancarios</p>
            @endif
        </div>
    </div>
</div>

<!-- Comprobante de Pago -->
@if($pago->comprobante_pago)
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Comprobante de Pago</h2>
    
    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">Comprobante Disponible</h3>
                <div class="mt-2 text-sm text-green-700">
                    <p>El comprobante de pago ha sido generado y está disponible para descarga. Puedes revisarlo antes de confirmar la recepción del pago.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex justify-center">
        <a href="{{ route('consultor.pagos.comprobante', $pago->id) }}" target="_blank" 
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[#4682B4] hover:bg-[#36648B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4] shadow-lg">
            <svg class="-ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
            </svg>
            Descargar Comprobante de Pago
        </a>
    </div>
</div>
@endif

<!-- Formulario de Confirmación -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Confirmación de Recepción</h2>
    
    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Instrucciones</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Por favor, confirma si has recibido el pago en tu cuenta bancaria. Si hay algún problema, puedes reportarlo en los comentarios.</p>
                </div>
            </div>
        </div>
    </div>
    
    <form action="{{ route('consultor.pagos.confirmar', $pago->id) }}" method="POST">
        @csrf
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">¿Has recibido el pago?</label>
            <div class="space-y-2">
                <div class="flex items-center">
                    <input id="confirmado" name="accion" type="radio" value="confirmar" class="focus:ring-[#4682B4] h-4 w-4 text-[#4682B4] border-gray-300" required>
                    <label for="confirmado" class="ml-3 block text-sm font-medium text-gray-700">
                        Sí, he recibido el pago correctamente
                    </label>
                </div>
                <div class="flex items-center">
                    <input id="rechazado" name="accion" type="radio" value="rechazar" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" required>
                    <label for="rechazado" class="ml-3 block text-sm font-medium text-gray-700">
                        No, hay un problema con el pago
                    </label>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <label for="comentario" class="block text-sm font-medium text-gray-700 mb-1">Comentarios (opcional)</label>
            <textarea id="comentario" name="comentario" rows="4"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50"
                      placeholder="Agrega cualquier comentario adicional sobre el pago recibido o reporta algún problema"></textarea>
        </div>
        
        <div class="flex justify-end space-x-3">
            <a href="{{ route('consultor.pagos.show', $pago->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[#4682B4] hover:bg-[#36648B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Enviar Confirmación
            </button>
        </div>
    </form>
</div>
@endsection