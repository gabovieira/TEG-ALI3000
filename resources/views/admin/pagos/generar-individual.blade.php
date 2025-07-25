@extends('layouts.admin')

@section('title', 'Generar Pago Individual - ALI3000')

@section('page-title', 'Generar Pago Individual')

@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#000000]">Generar Pago Individual</h1>
    <p class="text-[#708090]">Genera un pago individual para un consultor específico</p>
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

<!-- Formulario de Generación de Pago Individual -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Seleccionar Consultor y Período</h2>
    
    <form id="formCalcularPago" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Consultor -->
        <div>
            <label for="consultor_id" class="block text-sm font-medium text-gray-700 mb-1">Consultor <span class="text-red-500">*</span></label>
            <select id="consultor_id" name="consultor_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                <option value="">Seleccionar consultor</option>
                @foreach($consultores as $consultor)
                    <option value="{{ $consultor->id }}">
                        {{ $consultor->primer_nombre }} {{ $consultor->primer_apellido }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Empresa -->
        <div>
            <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-1">Empresa <span class="text-red-500">*</span></label>
            <select id="empresa_id" name="empresa_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
                <option value="">Seleccionar empresa</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Fecha Inicio -->
        <div>
            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio <span class="text-red-500">*</span></label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
        </div>
        
        <!-- Fecha Fin -->
        <div>
            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin <span class="text-red-500">*</span></label>
            <input type="date" id="fecha_fin" name="fecha_fin" required 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50">
        </div>
        
        <!-- Información de Tasa BCV -->
        <div class="md:col-span-2">
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-blue-800">Información de Tasa BCV</h3>
                        @if($tasaBcv)
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Tasa actual: <span class="font-semibold">{{ number_format($tasaBcv->tasa, 4) }} Bs/USD</span></p>
                                <p>Fecha: {{ \Carbon\Carbon::parse($tasaBcv->fecha_registro)->format('d/m/Y') }}</p>
                                <p class="mt-1">Esta tasa será utilizada para los cálculos de pagos.</p>
                            </div>
                        @else
                            <div class="mt-2 text-sm text-red-700">
                                <p>No hay tasa BCV disponible. Por favor, actualice la tasa antes de generar pagos.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botón de Calcular -->
        <div class="md:col-span-2 flex justify-center mt-4">
            <button type="button" id="btnCalcular" onclick="calcularPago()" style="background-color: #0047AB; color: white; padding: 12px 32px; border-radius: 6px; font-weight: bold; font-size: 18px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);" {{ !$tasaBcv ? 'disabled' : '' }}>
                <svg style="width: 24px; height: 24px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span>CALCULAR PAGO</span>
            </button>
        </div>
    </form>
</div>

<!-- Resultados del Cálculo (inicialmente oculto) -->
<div id="resultadosCalculo" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6 hidden">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Resultados del Cálculo</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Información del Consultor -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-2">Información del Consultor</h3>
            <p><span class="font-medium">Nombre:</span> <span id="resultadoNombreConsultor"></span></p>
            <p><span class="font-medium">Email:</span> <span id="resultadoEmailConsultor"></span></p>
            <p><span class="font-medium">Empresa:</span> <span id="resultadoEmpresa"></span></p>
            <p><span class="font-medium">Período:</span> <span id="resultadoPeriodo"></span></p>
        </div>
        
        <!-- Resumen del Cálculo -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-800 mb-2">Resumen del Cálculo</h3>
            <p><span class="font-medium">Horas Aprobadas:</span> <span id="resultadoHoras"></span></p>
            <p><span class="font-medium">Tarifa por Hora:</span> $<span id="resultadoTarifa"></span> USD</p>
            <p><span class="font-medium">Tasa BCV:</span> <span id="resultadoTasaBcv"></span> Bs/USD</p>
            <p><span class="font-medium">Fecha Tasa:</span> <span id="resultadoFechaTasa"></span></p>
        </div>
        
        <!-- Detalles del Cálculo -->
        <div class="md:col-span-2">
            <h3 class="text-md font-medium text-gray-800 mb-2">Detalles del Cálculo</h3>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">$<span id="resultadoMontoBaseDivisas"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Bs <span id="resultadoMontoBaseBs"></span></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">IVA (<span id="resultadoIvaPorcentaje"></span>%)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">$<span id="resultadoIvaDivisas"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Bs <span id="resultadoIvaBs"></span></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">Total con IVA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">$<span id="resultadoTotalConIvaDivisas"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">Bs <span id="resultadoTotalConIvaBs"></span></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ISLR (<span id="resultadoIslrPorcentaje"></span>%)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">$<span id="resultadoIslrDivisas"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Bs <span id="resultadoIslrBs"></span></td>
                        </tr>
                        <tr class="bg-blue-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 font-bold">TOTAL A PAGAR</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 text-right font-bold">$<span id="resultadoTotalMenosIslrDivisas"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 text-right font-bold">Bs <span id="resultadoTotalMenosIslrBs"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Datos Bancarios -->
        <div class="md:col-span-2" id="seccionDatosBancarios">
            <h3 class="text-md font-medium text-gray-800 mb-2">Datos Bancarios</h3>
            <div id="datosBancariosContenido">
                <!-- Aquí se mostrarán los datos bancarios -->
            </div>
        </div>
        
        <!-- Formulario para Generar Pago -->
        <div class="md:col-span-2 mt-4">
            <form id="formGenerarPago" action="{{ route('admin.pagos.generar') }}" method="POST">
                <input type="hidden" name="tipo_pago" value="individual">
                @csrf
                <input type="hidden" id="consultor_id_hidden" name="consultor_id">
                <input type="hidden" id="empresa_id_hidden" name="empresa_id">
                <input type="hidden" id="fecha_inicio_hidden" name="fecha_inicio">
                <input type="hidden" id="fecha_fin_hidden" name="fecha_fin">
                <input type="hidden" id="datos_bancarios_id" name="datos_bancarios_id">
                
                <div class="mb-4">
                    <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones (opcional)</label>
                    <textarea id="observaciones" name="observaciones" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="flex justify-center">
                    <button type="submit" style="background-color: #0047AB; color: white; padding: 12px 32px; border-radius: 6px; font-weight: bold; font-size: 18px; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <svg style="width: 24px; height: 24px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>GENERAR PAGO</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Información Importante -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Información Importante</h2>
    
    <div class="space-y-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Verificación de Horas</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Solo se generarán pagos para las horas que hayan sido aprobadas en el sistema.
                </p>
            </div>
        </div>
        
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Pagos Duplicados</h3>
                <p class="mt-1 text-sm text-gray-600">
                    El sistema verificará que no existan pagos duplicados para el mismo consultor, empresa y período.
                </p>
            </div>
        </div>
        
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Datos Bancarios</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Asegúrese de que el consultor tenga datos bancarios registrados en el sistema.
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
                <h3 class="text-sm font-medium text-gray-800">Período Personalizado</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Puede seleccionar un período personalizado para generar pagos fuera de las quincenas regulares.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para calcular el pago
    function calcularPago() {
        // Validar formulario
        const consultorId = document.getElementById('consultor_id').value;
        const empresaId = document.getElementById('empresa_id').value;
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        
        if (!consultorId || !empresaId || !fechaInicio || !fechaFin) {
            alert('Por favor, complete todos los campos requeridos.');
            return;
        }
        
        // Deshabilitar botón de calcular y mostrar carga
        const btnCalcular = document.getElementById('btnCalcular');
        btnCalcular.disabled = true;
        btnCalcular.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Calculando...';
        
        // Realizar petición AJAX
        fetch(`{{ url('admin/pagos/calcular-individual') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                consultor_id: consultorId,
                empresa_id: empresaId,
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Error al calcular el pago');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mostrar resultados
                mostrarResultados(data);
                
                // Actualizar campos ocultos del formulario
                document.getElementById('consultor_id_hidden').value = consultorId;
                document.getElementById('empresa_id_hidden').value = empresaId;
                document.getElementById('fecha_inicio_hidden').value = fechaInicio;
                document.getElementById('fecha_fin_hidden').value = fechaFin;
                
                // Mostrar sección de resultados
                document.getElementById('resultadosCalculo').classList.remove('hidden');
                
                // Cargar datos bancarios
                cargarDatosBancarios(consultorId);
            } else {
                alert(data.message || 'Error al calcular el pago');
            }
        })
        .catch(error => {
            alert(error.message || 'Error al calcular el pago');
        })
        .finally(() => {
            // Restaurar botón de calcular
            btnCalcular.disabled = false;
            btnCalcular.innerHTML = '<svg style="width: 24px; height: 24px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg><span>CALCULAR PAGO</span>';
        });
    }
    
    // Función para mostrar los resultados del cálculo
    function mostrarResultados(data) {
        // Información del Consultor
        document.getElementById('resultadoNombreConsultor').textContent = data.consultor.nombre;
        document.getElementById('resultadoEmailConsultor').textContent = data.consultor.email;
        document.getElementById('resultadoEmpresa').textContent = data.empresa.nombre;
        document.getElementById('resultadoPeriodo').textContent = `${formatearFecha(data.periodo.fecha_inicio)} - ${formatearFecha(data.periodo.fecha_fin)}`;
        
        // Resumen del Cálculo
        document.getElementById('resultadoHoras').textContent = data.calculo.horas;
        document.getElementById('resultadoTarifa').textContent = formatearNumero(data.calculo.tarifa);
        document.getElementById('resultadoTasaBcv').textContent = formatearNumero(data.calculo.tasa_cambio, 4);
        document.getElementById('resultadoFechaTasa').textContent = formatearFecha(data.calculo.fecha_tasa_bcv);
        
        // Detalles del Cálculo
        document.getElementById('resultadoMontoBaseDivisas').textContent = formatearNumero(data.calculo.monto_base_divisas);
        document.getElementById('resultadoMontoBaseBs').textContent = formatearNumero(data.calculo.monto_base_bs);
        
        document.getElementById('resultadoIvaPorcentaje').textContent = formatearNumero(data.calculo.iva_porcentaje);
        document.getElementById('resultadoIvaDivisas').textContent = formatearNumero(data.calculo.iva_divisas);
        document.getElementById('resultadoIvaBs').textContent = formatearNumero(data.calculo.iva_bs);
        
        document.getElementById('resultadoTotalConIvaDivisas').textContent = formatearNumero(data.calculo.total_con_iva_divisas);
        document.getElementById('resultadoTotalConIvaBs').textContent = formatearNumero(data.calculo.total_con_iva_bs);
        
        document.getElementById('resultadoIslrPorcentaje').textContent = formatearNumero(data.calculo.islr_porcentaje);
        document.getElementById('resultadoIslrDivisas').textContent = formatearNumero(data.calculo.islr_divisas);
        document.getElementById('resultadoIslrBs').textContent = formatearNumero(data.calculo.islr_bs);
        
        document.getElementById('resultadoTotalMenosIslrDivisas').textContent = formatearNumero(data.calculo.total_menos_islr_divisas);
        document.getElementById('resultadoTotalMenosIslrBs').textContent = formatearNumero(data.calculo.total_menos_islr_bs);
        
        // Datos Bancarios
        if (data.datos_bancarios) {
            document.getElementById('datos_bancarios_id').value = data.datos_bancarios.id;
            
            const datosBancariosHTML = `
                <div class="bg-gray-50 rounded-lg p-4">
                    <p><span class="font-medium">Banco:</span> ${data.datos_bancarios.banco}</p>
                    <p><span class="font-medium">Tipo de Cuenta:</span> ${data.datos_bancarios.tipo_cuenta === 'ahorro' ? 'Ahorro' : 'Corriente'}</p>
                    <p><span class="font-medium">Número de Cuenta:</span> ${data.datos_bancarios.numero_cuenta}</p>
                    <p><span class="font-medium">Cédula/RIF:</span> ${data.datos_bancarios.cedula_rif}</p>
                    <p><span class="font-medium">Titular:</span> ${data.datos_bancarios.titular}</p>
                </div>
            `;
            
            document.getElementById('datosBancariosContenido').innerHTML = datosBancariosHTML;
        } else {
            document.getElementById('datosBancariosContenido').innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Advertencia</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>El consultor no tiene datos bancarios registrados. Por favor, registre los datos bancarios antes de procesar el pago.</p>
                                <p class="mt-2">
                                    <a href="{{ url('admin/consultores') }}/${data.consultor.id}/datos-bancarios" class="text-yellow-800 font-medium underline">
                                        Registrar datos bancarios
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    // Función para cargar datos bancarios
    function cargarDatosBancarios(consultorId) {
        fetch(`{{ url('admin/datos-bancarios') }}/${consultorId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.datos_bancarios.length > 0) {
                // Si hay múltiples cuentas bancarias, mostrar selector
                if (data.datos_bancarios.length > 1) {
                    let selectHTML = `
                        <div class="mb-4">
                            <label for="datos_bancarios_selector" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Cuenta Bancaria <span class="text-red-500">*</span></label>
                            <select id="datos_bancarios_selector" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" onchange="actualizarDatosBancarios(this.value)">
                    `;
                    
                    data.datos_bancarios.forEach(cuenta => {
                        const esPrincipal = cuenta.es_principal ? ' (Principal)' : '';
                        selectHTML += `<option value="${cuenta.id}" ${cuenta.es_principal ? 'selected' : ''}>${cuenta.banco} - ${cuenta.numero_cuenta}${esPrincipal}</option>`;
                    });
                    
                    selectHTML += `
                            </select>
                        </div>
                    `;
                    
                    document.getElementById('datosBancariosContenido').innerHTML = selectHTML + document.getElementById('datosBancariosContenido').innerHTML;
                    
                    // Actualizar campo oculto con la cuenta principal
                    const cuentaPrincipal = data.datos_bancarios.find(cuenta => cuenta.es_principal);
                    if (cuentaPrincipal) {
                        document.getElementById('datos_bancarios_id').value = cuentaPrincipal.id;
                    } else {
                        document.getElementById('datos_bancarios_id').value = data.datos_bancarios[0].id;
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error al cargar datos bancarios:', error);
        });
    }
    
    // Función para actualizar datos bancarios seleccionados
    function actualizarDatosBancarios(datosBancariosId) {
        document.getElementById('datos_bancarios_id').value = datosBancariosId;
    }
    
    // Función para formatear números
    function formatearNumero(numero, decimales = 2) {
        return parseFloat(numero).toFixed(decimales).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    // Función para formatear fechas
    function formatearFecha(fechaStr) {
        const fecha = new Date(fechaStr);
        return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }
</script>
@endsection