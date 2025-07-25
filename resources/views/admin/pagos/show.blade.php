@extends('layouts.admin')

@section('title', 'Detalle de Pago - ALI3000')

@section('page-title', 'Detalle de Pago')

@section('admin-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-[#000000]">Detalle de Pago</h1>
        <p class="text-[#708090]">Información completa del pago</p>
    </div>
    
    <div>
        <a href="{{ route('admin.pagos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Volver a la lista
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

@if(session('warning'))
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
        {{ session('warning') }}
    </div>
@endif

<!-- Información del Pago -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-medium text-[#000000]">Información del Pago</h2>
        
        <div class="flex space-x-2">
            @if($pago->comprobante_pago)
                <a href="{{ route('admin.pagos.comprobante', $pago->id) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#4682B4] hover:bg-[#36648B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4682B4]">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                    </svg>
                    Descargar Comprobante
                </a>
            @endif
            
            @if($pago->estado == 'pendiente')
                <button type="button" onclick="mostrarModalMarcarPagado()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Marcar como Pagado
                </button>
                
                <button type="button" onclick="mostrarModalAnular()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Anular
                </button>
            @endif
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Estado del Pago -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-2">Estado del Pago</h3>
            
            <div class="flex items-center mb-2">
                <span class="mr-2">Estado:</span>
                @if($pago->estado == 'pendiente')
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Pendiente
                    </span>
                @elseif($pago->estado == 'procesado')
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        Procesado
                    </span>
                @elseif($pago->estado == 'confirmado')
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Confirmado
                    </span>
                @elseif($pago->estado == 'rechazado')
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        Rechazado
                    </span>
                @elseif($pago->estado == 'anulado')
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                        Anulado
                    </span>
                @endif
            </div>
            
            @if($pago->fecha_procesado)
                <p><span class="font-medium">Procesado:</span> {{ $pago->fecha_procesado->format('d/m/Y H:i') }}</p>
                <p><span class="font-medium">Por:</span> {{ $pago->procesador->primer_nombre ?? '' }} {{ $pago->procesador->primer_apellido ?? '' }}</p>
            @endif
            
            @if($pago->fecha_confirmacion)
                <p><span class="font-medium">Confirmado:</span> {{ $pago->fecha_confirmacion->format('d/m/Y H:i') }}</p>
                @if($pago->comentario_confirmacion)
                    <p><span class="font-medium">Comentario:</span> {{ $pago->comentario_confirmacion }}</p>
                @endif
            @endif
        </div>
        
        <!-- Información del Consultor -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-2">Información del Consultor</h3>
            <p><span class="font-medium">Nombre:</span> {{ $pago->consultor->primer_nombre }} {{ $pago->consultor->primer_apellido }}</p>
            <p><span class="font-medium">Email:</span> {{ $pago->consultor->email }}</p>
            <p><span class="font-medium">Nivel:</span> {{ $pago->consultor->datosLaborales->nivel_desarrollo ?? 'No definido' }}</p>
            @if($pago->detalles->count() > 0)
                <p><span class="font-medium">Empresas:</span> 
                    @foreach($pago->detalles as $detalle)
                        {{ $detalle->empresa->nombre }}@if(!$loop->last), @endif
                    @endforeach
                </p>
            @endif
        </div>
        
        <!-- Información del Período -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-2">Información del Período</h3>
            <p><span class="font-medium">Período:</span> {{ $pago->nombre_quincena }}</p>
            <p><span class="font-medium">Horas:</span> {{ $pago->total_horas }}</p>
            <p><span class="font-medium">Fecha Creación:</span> {{ $pago->created_at ? $pago->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
        </div>
    </div>
</div>

<!-- Detalles del Cálculo -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Detalles del Cálculo</h2>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concepto</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">USD</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Bs</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Monto Base</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($pago->monto_total, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Bs {{ number_format($pago->monto_total * $pago->tasa_cambio, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">IVA ({{ number_format($pago->iva_porcentaje, 2) }}%)</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($pago->iva_monto, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Bs {{ number_format($pago->iva_monto * $pago->tasa_cambio, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">Total con IVA</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">${{ number_format($pago->monto_total + $pago->iva_monto, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">Bs {{ number_format(($pago->monto_total + $pago->iva_monto) * $pago->tasa_cambio, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ISLR ({{ number_format($pago->islr_porcentaje, 2) }}%)</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($pago->islr_monto, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Bs {{ number_format($pago->islr_monto * $pago->tasa_cambio, 2) }}</td>
                </tr>
                <tr class="bg-blue-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 font-bold">TOTAL A PAGAR</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 text-right font-bold">${{ number_format($pago->monto_neto, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 text-right font-bold">Bs {{ number_format($pago->monto_neto * $pago->tasa_cambio, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 bg-gray-50 rounded-lg p-4">
        <p><span class="font-medium">Tasa BCV:</span> {{ number_format($pago->tasa_cambio, 4) }} Bs/USD</p>
        <p><span class="font-medium">Fecha Tasa:</span> {{ $pago->fecha_tasa_bcv ? $pago->fecha_tasa_bcv->format('d/m/Y') : 'N/A' }}</p>
    </div>
</div>

<!-- Datos Bancarios -->
@if($pago->datosBancarios)
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Datos Bancarios</h2>
    
    <div class="bg-gray-50 rounded-lg p-4">
        <p><span class="font-medium">Banco:</span> {{ $pago->datosBancarios->banco }}</p>
        <p><span class="font-medium">Tipo de Cuenta:</span> {{ $pago->datosBancarios->tipo_cuenta === 'ahorro' ? 'Ahorro' : 'Corriente' }}</p>
        <p><span class="font-medium">Número de Cuenta:</span> {{ $pago->datosBancarios->numero_cuenta }}</p>
        <p><span class="font-medium">Cédula/RIF:</span> {{ $pago->datosBancarios->cedula_rif }}</p>
        <p><span class="font-medium">Titular:</span> {{ $pago->datosBancarios->titular }}</p>
    </div>
</div>
@endif

<!-- Observaciones -->
@if($pago->observaciones)
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Observaciones</h2>
    
    <div class="bg-gray-50 rounded-lg p-4">
        <p>{{ $pago->observaciones }}</p>
    </div>
</div>
@endif

<!-- Modal Marcar como Pagado -->
<div id="modalMarcarPagado" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Marcar como Pagado</h3>
        
        <form id="formMarcarPagado" action="{{ route('admin.pagos.pagar', $pago->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="fecha_pago" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Pago <span class="text-red-500">*</span></label>
                <input type="date" id="fecha_pago" name="fecha_pago" value="{{ date('Y-m-d') }}" required 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
            </div>
            
            <div class="mb-4">
                <label for="referencia_bancaria" class="block text-sm font-medium text-gray-700 mb-1">Referencia Bancaria</label>
                <input type="text" id="referencia_bancaria" name="referencia_bancaria" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50"
                       placeholder="Número de referencia de la transferencia">
            </div>
            
            <div class="mb-4">
                <label for="observaciones_pago" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea id="observaciones_pago" name="observaciones" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50"
                          placeholder="Detalles adicionales sobre el pago realizado"></textarea>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Importante</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Al marcar como pagado, el consultor recibirá una notificación para confirmar la recepción del pago.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="cerrarModalMarcarPagado()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Marcar como Pagado
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Anular -->
<div id="modalAnular" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Anulación</h3>
        
        <form id="formAnular" action="{{ route('admin.pagos.anular', $pago->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo de Anulación <span class="text-red-500">*</span></label>
                <textarea id="motivo" name="motivo" rows="3" required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="cerrarModalAnular()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit" style="background-color: #DC2626; color: white; padding: 8px 16px; border-radius: 6px; font-weight: bold; font-size: 14px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>ANULAR PAGO</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Funciones para modales
    function mostrarModalMarcarPagado() {
        document.getElementById('modalMarcarPagado').classList.remove('hidden');
    }
    
    function cerrarModalMarcarPagado() {
        document.getElementById('modalMarcarPagado').classList.add('hidden');
        document.getElementById('fecha_pago').value = '{{ date("Y-m-d") }}';
        document.getElementById('referencia_bancaria').value = '';
        document.getElementById('observaciones_pago').value = '';
    }
    
    function mostrarModalAnular() {
        document.getElementById('modalAnular').classList.remove('hidden');
    }
    
    function cerrarModalAnular() {
        document.getElementById('modalAnular').classList.add('hidden');
        document.getElementById('motivo').value = '';
    }
</script>
@endsection