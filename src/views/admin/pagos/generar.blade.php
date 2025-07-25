
@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Generar Pagos</h1>
    
    <!-- Alertas y Mensajes -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <!-- Tabs de Tipo de Pago -->
        <div class="mb-6">
            <div class="flex gap-4 border-b pb-2 mb-4">
                <button type="button" 
                    class="tab-btn {{ request('tipo') != 'individual' ? 'bg-blue-600 text-white' : 'bg-gray-100' }} px-4 py-2 rounded-t-lg"
                    onclick="cambiarTipo('quincenal')"
                    id="tab-quincena">
                    Por Quincena
                </button>
                <button type="button" 
                    class="tab-btn {{ request('tipo') == 'individual' ? 'bg-blue-600 text-white' : 'bg-gray-100' }} px-4 py-2 rounded-t-lg"
                    onclick="cambiarTipo('individual')"
                    id="tab-individual">
                    Pago Individual
                </button>
            </div>

            <!-- Formulario de Búsqueda -->
            <form method="GET" action="{{ route('admin.pagos.generar') }}" class="space-y-4" id="form-busqueda">
                <input type="hidden" name="tipo" value="{{ request('tipo', 'quincenal') }}" id="tipo-pago">
                
                <!-- Selección de Período -->
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Período <span class="text-red-500">*</span>
                    </label>
                    <select name="periodo" class="form-select w-full rounded-md border-gray-300" required onchange="this.form.submit()">
                        <option value="">Seleccionar período</option>
                        @foreach($periodos as $periodo)
                            <option value="{{ $periodo['id'] }}" 
                                {{ request('periodo') == $periodo['id'] ? 'selected' : '' }}
                                data-inicio="{{ $periodo['inicio'] }}"
                                data-fin="{{ $periodo['fin'] }}">
                                {{ $periodo['nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selector de Empresa (Opcional) -->
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Empresa (Opcional)
                    </label>
                    <select name="empresa_id" class="form-select w-full rounded-md border-gray-300" onchange="this.form.submit()">
                        <option value="">Todas las empresas</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
                @if(isset($consultores) && count($consultores))
                <!-- Info Tasa BCV -->
                <div class="bg-blue-50 p-4 rounded-lg mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium">Información de Tasa BCV</p>
                            <p class="text-sm">Tasa actual: {{ number_format($tasa_bcv->valor, 4, ',', '.') }} Bs/USD</p>
                            <p class="text-sm">Fecha: {{ \Carbon\Carbon::parse($tasa_bcv->fecha)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lista de Consultores -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-4">Consultores con Horas Pendientes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($consultores as $consultor)
                        <div class="bg-white rounded-lg shadow p-4 border {{ !$consultor['tiene_datos_bancarios'] ? 'border-red-300' : 'border-gray-200' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="font-medium">{{ $consultor['nombre'] }}</h4>
                                    <p class="text-sm text-gray-600">Total: {{ $consultor['horas'] }} horas</p>
                                </div>
                                @if(!$consultor['tiene_datos_bancarios'])
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Sin datos bancarios
                                </span>
                                @endif
                            </div>
                            
                            <!-- Desglose por Empresa -->
                            <div class="space-y-2 mb-3">
                                @foreach($consultor['empresas'] as $empresa)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $empresa['nombre'] }}:</span>
                                    <span class="font-medium">{{ $empresa['horas'] }} horas</span>
                                </div>
                                @endforeach
                            </div>

                            <!-- Botones de Acción -->
                            <div class="mt-4 flex justify-end space-x-2">
                                @if($consultor['tiene_datos_bancarios'])
                                <form method="POST" action="{{ route('admin.pagos.calcular') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="periodo_id" value="{{ request('periodo') }}">
                                    <input type="hidden" name="consultor_id" value="{{ $consultor['id'] }}">
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Calcular Pago
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('admin.consultores.datos-bancarios', $consultor['id']) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Agregar Datos Bancarios
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay consultores con horas pendientes</h3>
                    <p class="mt-1 text-sm text-gray-500">Para el período seleccionado no se encontraron horas pendientes de pago.</p>
                </div>
                @endif
                        </tbody>
                    </table>
                </div>
                @endif
                @if(isset($pago_calculado))
                <div class="mb-4">
                    <h2 class="text-xl font-bold mb-2">Cálculo de Pago</h2>
                    @include('components.PagoCard', [
                        'avatar' => $pago_calculado->consultor->avatar ?? null,
                        'nombre' => $pago_calculado->consultor->nombre,
                        'empresa' => $pago_calculado->empresa->nombre ?? 'Múltiples',
                        'monto' => $pago_calculado->total_con_iva_divisas,
                        'estado' => 'pendiente'
                    ])
                    @include('components.CalculoDetalle', [
                        'detalles' => $pago_calculado->detalles,
                        'iva_divisas' => $pago_calculado->iva_divisas,
                        'iva_porcentaje' => $pago_calculado->iva_porcentaje,
                        'islr_divisas' => $pago_calculado->islr_divisas,
                        'islr_porcentaje' => $pago_calculado->islr_porcentaje,
                        'total_con_iva_divisas' => $pago_calculado->total_con_iva_divisas
                    ])
                    <form method="POST" action="{{ route('admin.pagos.confirmar') }}">
                        @csrf
                        <input type="hidden" name="quincena" value="{{ request('quincena') }}">
                        <input type="hidden" name="consultor_id" value="{{ $pago_calculado->consultor->id }}">
                        <button type="submit" class="btn btn-success w-full mt-4">Confirmar y Registrar Pago</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function cambiarTipo(tipo) {
    document.getElementById('tipo-pago').value = tipo;
    document.getElementById('form-busqueda').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const tipo = document.getElementById('tipo-pago').value;
    const tabs = document.querySelectorAll('.tab-btn');
    
    tabs.forEach(tab => {
        if ((tipo === 'individual' && tab.id === 'tab-individual') || 
            (tipo !== 'individual' && tab.id === 'tab-quincena')) {
            tab.classList.add('bg-blue-600', 'text-white');
            tab.classList.remove('bg-gray-100');
        } else {
            tab.classList.remove('bg-blue-600', 'text-white');
            tab.classList.add('bg-gray-100');
        }
    });
});
</script>
@endpush

@endsection
                        <select name="empresa" class="form-select w-full">
                            <option value="">Seleccionar empresa</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Quincena <span class="text-red-500">*</span></label>
                        <select name="quincena_individual" class="form-select w-full">
                            <option value="">Seleccionar quincena</option>
                            @foreach($quincenas as $q)
                                <option value="{{ $q }}">{{ $q }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="bg-blue-50 rounded p-4">
                        <div class="font-semibold mb-2">Información de Tasa BCV</div>
                        <div class="text-sm">Tasa actual: <span class="font-bold">{{ $tasa_bcv->valor ?? 'N/A' }} Bs/USD</span></div>
                        <div class="text-xs text-gray-500">Fecha: {{ $tasa_bcv->fecha ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">Esta tasa será utilizada para los cálculos de pagos.</div>
                    </div>
                </div>
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary w-full">+ GENERAR PAGOS</button>
                </div>
            </form>
        </div>
        <div class="bg-blue-50 rounded p-4 mb-4">
            <div class="font-semibold mb-2">Información Importante</div>
            <ul class="list-disc ml-4 text-sm">
                <li>Solo se generarán pagos para horas aprobadas.</li>
                <li>Se evitarán pagos duplicados por consultor/quincena.</li>
                <li>La tasa BCV se usará para cálculos, puede ingresar manual si no está disponible.</li>
                <li>Los porcentajes de IVA e ISLR se aplican según configuración fiscal vigente.</li>
            </ul>
        </div>
        @if(isset($pagos_preview))
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-2">Vista Previa de Pagos</h2>
                @foreach($pagos_preview as $pago)
                    @include('components.PagoCard', [
                        'avatar' => $pago->consultor->avatar ?? null,
                        'nombre' => $pago->consultor->nombre,
                        'empresa' => $pago->empresa->nombre ?? 'Múltiples',
                        'monto' => $pago->total_con_iva_divisas,
                        'estado' => 'pendiente'
                    ])
                    @include('components.CalculoDetalle', [
                        'detalles' => $pago->detalles,
                        'iva_divisas' => $pago->iva_divisas,
                        'iva_porcentaje' => $pago->iva_porcentaje,
                        'islr_divisas' => $pago->islr_divisas,
                        'islr_porcentaje' => $pago->islr_porcentaje,
                        'total_con_iva_divisas' => $pago->total_con_iva_divisas
                    ])
                @endforeach
            </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabQuincena = document.getElementById('tab-quincena');
        const tabIndividual = document.getElementById('tab-individual');
        const formQuincena = document.getElementById('form-quincena');
        const formIndividual = document.getElementById('form-individual');

        // Estado inicial: mostrar quincena
        tabQuincena.classList.add('btn-primary');
        tabQuincena.classList.remove('btn-outline');
        tabIndividual.classList.remove('btn-primary');
        tabIndividual.classList.add('btn-outline');
        formQuincena.style.display = 'block';
        formIndividual.style.display = 'none';

        tabQuincena.addEventListener('click', function(e) {
            e.preventDefault();
            tabQuincena.classList.add('btn-primary');
            tabQuincena.classList.remove('btn-outline');
            tabIndividual.classList.remove('btn-primary');
            tabIndividual.classList.add('btn-outline');
            formQuincena.style.display = 'block';
            formIndividual.style.display = 'none';
        });
        tabIndividual.addEventListener('click', function(e) {
            e.preventDefault();
            tabIndividual.classList.add('btn-primary');
            tabIndividual.classList.remove('btn-outline');
            tabQuincena.classList.remove('btn-primary');
            tabQuincena.classList.add('btn-outline');
            formQuincena.style.display = 'none';
            formIndividual.style.display = 'block';
        });
    });
</script>
@endsection
