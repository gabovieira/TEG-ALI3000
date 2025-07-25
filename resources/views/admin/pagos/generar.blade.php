@extends('layouts.admin')

@section('title', 'Generar Pagos - ALI3000')

@section('page-title', 'Generar Pagos')

@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#000000]">Generar Pagos por Consultor</h1>
    <p class="text-[#708090]">Genera pagos para consultores basado en horas aprobadas por quincena</p>
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

<!-- Información del proceso -->
<div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Proceso de Generación de Pagos</h3>
            <p class="mt-1 text-sm text-blue-700">
                El sistema generará <strong>un pago por consultor</strong> que incluirá todas las horas aprobadas de todas las empresas para la quincena seleccionada. 
                Cada pago mostrará el desglose detallado por empresa.
            </p>
        </div>
    </div>
</div>

<!-- Formulario de Generación de Pagos -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Generar Pagos por Quincena</h2>
    
    <form action="{{ route('admin.pagos.generar') }}" method="POST" id="form-generar-pagos" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf
        
        <!-- Quincena -->
        <div>
            <label for="quincena" class="block text-sm font-medium text-gray-700 mb-1">Quincena <span class="text-red-500">*</span></label>
            <select id="quincena" name="quincena" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" required>
                <option value="">Seleccionar quincena</option>
                @foreach($quincenas as $valor => $nombre)
                    <option value="{{ $valor }}">{{ $nombre }}</option>
                @endforeach
            </select>
            @error('quincena')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Consultor específico (requerido) -->
        <div>
            <label for="consultor_id" class="block text-sm font-medium text-gray-700 mb-1">Consultor <span class="text-red-500">*</span></label>
            <select id="consultor_id" name="consultor_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#4682B4] focus:ring focus:ring-[#4682B4] focus:ring-opacity-50" required>
                <option value="">Seleccionar consultor</option>
                @foreach($consultores as $consultor)
                    <option value="{{ $consultor->id }}">{{ $consultor->primer_nombre }} {{ $consultor->primer_apellido }}</option>
                @endforeach
            </select>
            @error('consultor_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Seleccione el consultor para generar su pago individual</p>
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
        
        <!-- Vista previa del cálculo -->
        <div id="calculo-preview" class="md:col-span-2 hidden">
            <div class="bg-gray-50 border border-gray-200 rounded-md p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Cálculo de Honorarios</h3>
                <div id="calculo-content" class="space-y-4">
                    <!-- Se llenará dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Datos bancarios del consultor -->
        <div id="datos-bancarios-preview" class="md:col-span-2 hidden">
            <div class="bg-blue-50 border border-blue-200 rounded-md p-6">
                <h3 class="text-lg font-medium text-blue-800 mb-4">Datos Bancarios del Consultor</h3>
                <div id="datos-bancarios-content" class="space-y-2">
                    <!-- Se llenará dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Botón de Generar -->
        <div class="md:col-span-2 flex justify-center mt-8 p-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <button type="submit" id="btn-generar" 
                    class="bg-blue-600 text-white px-12 py-4 rounded-lg font-bold text-xl flex items-center shadow-xl hover:bg-blue-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400" 
                    disabled>
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>CONFIRMAR Y GENERAR PAGO</span>
            </button>
        </div>
    </form>
</div>

<!-- Vista previa de consultores (opcional) -->
<div id="preview-consultores" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6 hidden">
    <h2 class="text-lg font-medium text-[#000000] mb-4">Vista Previa - Consultores con Horas Aprobadas</h2>
    <div id="consultores-content" class="space-y-3">
        <!-- Se llenará dinámicamente con JavaScript -->
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
                <h3 class="text-sm font-medium text-gray-800">Pago Individual por Consultor</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Seleccione un consultor específico para generar su pago individual basado en las horas aprobadas de la quincena.
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
                <h3 class="text-sm font-medium text-gray-800">Flujo de Confirmación</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Una vez generado el pago, se enviará al consultor para que confirme la recepción y se genere el comprobante final.
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
                    El sistema verificará que no existan pagos duplicados para el mismo consultor y período.
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
                <h3 class="text-sm font-medium text-gray-800">Tarifas</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Asegúrese de que todos los consultores tengan tarifas configuradas para las empresas correspondientes.
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
                <h3 class="text-sm font-medium text-gray-800">Quincenas</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Primera quincena: del 1 al 15 del mes<br>
                    Segunda quincena: del 16 al último día del mes
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('JavaScript cargado correctamente');
        
        const quincenaSelect = document.getElementById('quincena');
        const consultorSelect = document.getElementById('consultor_id');
        const calculoPreview = document.getElementById('calculo-preview');
        const calculoContent = document.getElementById('calculo-content');
        const datosBancariosPreview = document.getElementById('datos-bancarios-preview');
        const datosBancariosContent = document.getElementById('datos-bancarios-content');
        const btnGenerar = document.getElementById('btn-generar');
        
        console.log('Elementos encontrados:', {
            quincenaSelect: !!quincenaSelect,
            consultorSelect: !!consultorSelect,
            calculoPreview: !!calculoPreview,
            calculoContent: !!calculoContent,
            datosBancariosPreview: !!datosBancariosPreview,
            datosBancariosContent: !!datosBancariosContent,
            btnGenerar: !!btnGenerar
        });
        
        // Función para verificar si se puede habilitar el botón
        function verificarBoton() {
            const quincena = quincenaSelect.value;
            const consultorId = consultorSelect.value;
            
            // Habilitar botón si ambos campos están seleccionados
            if (quincena && consultorId) {
                btnGenerar.disabled = false;
                btnGenerar.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
                btnGenerar.classList.add('bg-blue-600', 'hover:bg-blue-700');
            } else {
                btnGenerar.disabled = true;
                btnGenerar.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
                btnGenerar.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            }
        }

        // Función para calcular honorarios del consultor
        function calcularHonorarios() {
            const quincena = quincenaSelect.value;
            const consultorId = consultorSelect.value;
            
            console.log('Calculando honorarios:', { quincena, consultorId });
            
            // Verificar botón
            verificarBoton();
            
            // Resetear vistas
            calculoPreview.classList.add('hidden');
            datosBancariosPreview.classList.add('hidden');
            
            if (quincena && consultorId) {
                calculoContent.innerHTML = '<p class="text-gray-500">Calculando honorarios...</p>';
                calculoPreview.classList.remove('hidden');
                
                // Construir URL para el cálculo
                const url = `{{ route('admin.pagos.calcular') }}?quincena=${quincena}&consultor_id=${consultorId}`;
                console.log('URL de cálculo:', url);
                
                fetch(url)
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            // Mostrar cálculo de honorarios
                            mostrarCalculoHonorarios(data.calculo);
                            
                            // Mostrar datos bancarios
                            mostrarDatosBancarios(data.datos_bancarios);
                            
                            // El botón ya está habilitado por verificarBoton()
                            // Solo mostrar advertencia si no hay datos bancarios
                            if (!data.datos_bancarios) {
                                console.warn('Consultor sin datos bancarios');
                            }
                        } else {
                            let errorHtml = `
                                <div class="p-4 bg-red-50 border border-red-200 rounded-md">
                                    <p class="text-sm text-red-800">${data.message}</p>
                            `;
                            
                            if (data.debug) {
                                errorHtml += `
                                    <div class="mt-2 text-xs text-gray-600">
                                        <p>Debug info:</p>
                                        <p>Total horas: ${data.debug.total_horas}</p>
                                        <p>Empresas: ${data.debug.empresas_count}</p>
                                        <p>Período: ${data.debug.fecha_inicio} a ${data.debug.fecha_fin}</p>
                                    </div>
                                `;
                            }
                            
                            errorHtml += '</div>';
                            calculoContent.innerHTML = errorHtml;
                        }
                    })
                    .catch(error => {
                        console.error('Error en fetch:', error);
                        calculoContent.innerHTML = `
                            <div class="p-4 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-sm text-red-800">Error al calcular: ${error.message}</p>
                            </div>
                        `;
                    });
            }
        }
        
        // Función para mostrar el cálculo de honorarios
        function mostrarCalculoHonorarios(calculo) {
            console.log('Datos de cálculo recibidos:', calculo);
            console.log('Empresas:', calculo.empresas);
            
            // Debug cada empresa individualmente
            calculo.empresas.forEach((emp, index) => {
                console.log(`Empresa ${index}:`, emp);
                console.log(`Tarifa tipo:`, typeof emp.tarifa, 'Valor:', emp.tarifa);
            });
            
            let html = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900">Desglose por Empresa</h4>
                        ${calculo.empresas.map(emp => `
                            <div class="bg-white border border-gray-200 rounded-md p-3">
                                <p class="font-medium text-gray-800">${emp.empresa_nombre || emp.nombre || 'Empresa'}</p>
                                <p class="text-sm text-gray-600">Horas: ${emp.horas || 0}</p>
                                <p class="text-sm text-gray-600">Tarifa (con IVA): $${Number(emp.tarifa || 0).toFixed(2)} USD/hora</p>
                                <p class="text-sm text-gray-600">Monto con IVA: $${Number(emp.monto_con_iva || 0).toFixed(2)} USD</p>
                                <p class="text-sm font-medium text-gray-800">Monto Base: $${Number(emp.monto_base || emp.subtotal || 0).toFixed(2)} USD</p>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900">Resumen de Cálculo</h4>
                        <div class="bg-white border border-gray-200 rounded-md p-4 space-y-2">
                            <div class="flex justify-between">
                                <span>Total Horas:</span>
                                <span class="font-medium">${Number(calculo.total_horas || 0)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Monto Base (sin IVA):</span>
                                <span>$${Number(calculo.monto_base || 0).toFixed(2)} USD</span>
                            </div>
                            <div class="flex justify-between">
                                <span>IVA (${Number(calculo.iva_porcentaje || 0)}%):</span>
                                <span class="text-blue-600">+$${Number(calculo.iva_monto || 0).toFixed(2)} USD</span>
                            </div>
                            <div class="flex justify-between">
                                <span>ISLR (${Number(calculo.islr_porcentaje || 0)}%):</span>
                                <span class="text-red-600">-$${Number(calculo.islr_monto || 0).toFixed(2)} USD</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total a Pagar:</span>
                                <span class="text-green-600">$${Number(calculo.total_pagar || 0).toFixed(2)} USD</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-semibold mt-3 pt-3 border-t-2 border-blue-300">
                                <span class="text-blue-800">En Bolívares:</span>
                                <span class="text-3xl font-black text-blue-900 bg-blue-100 px-4 py-2 rounded-lg shadow-md">Bs. ${Number(calculo.total_pagar_bs || 0).toFixed(2)}</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-2">
                                Tasa BCV: ${Number(calculo.tasa_bcv || 0).toFixed(4)} Bs/USD (${calculo.fecha_tasa_bcv || 'N/A'})
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            calculoContent.innerHTML = html;
        }
        
        // Función para mostrar datos bancarios
        function mostrarDatosBancarios(datosBancarios) {
            if (datosBancarios) {
                let html = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-blue-800">Banco:</p>
                            <p class="text-sm text-blue-700">${datosBancarios.banco}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Tipo de Cuenta:</p>
                            <p class="text-sm text-blue-700">${datosBancarios.tipo_cuenta === 'ahorro' ? 'Ahorro' : 'Corriente'}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Número de Cuenta:</p>
                            <p class="text-sm text-blue-700">${datosBancarios.numero_cuenta}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Titular:</p>
                            <p class="text-sm text-blue-700">${datosBancarios.titular}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Cédula/RIF:</p>
                            <p class="text-sm text-blue-700">${datosBancarios.cedula_rif}</p>
                        </div>
                    </div>
                `;
                
                datosBancariosContent.innerHTML = html;
                datosBancariosPreview.classList.remove('hidden');
            } else {
                datosBancariosContent.innerHTML = `
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-sm text-yellow-800">
                            <strong>Advertencia:</strong> El consultor no tiene datos bancarios registrados. 
                            Debe configurar sus datos bancarios antes de procesar el pago.
                        </p>
                    </div>
                `;
                datosBancariosPreview.classList.remove('hidden');
            }
        }
        
        // Configurar eventos
        if (quincenaSelect) {
            quincenaSelect.addEventListener('change', function() {
                console.log('Quincena cambiada:', this.value);
                verificarBoton();
                calcularHonorarios();
            });
        }
        
        if (consultorSelect) {
            consultorSelect.addEventListener('change', function() {
                console.log('Consultor cambiado:', this.value);
                verificarBoton();
                calcularHonorarios();
            });
        }
        
        // Verificar botón al cargar la página
        verificarBoton();
    });
</script>
@endpush
@endsection