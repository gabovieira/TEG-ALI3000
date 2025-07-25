{{-- CalculoDetalle: Desglose de cálculos fiscales y por empresa --}}
<div class="bg-gray-50 rounded-lg p-4 mb-2">
    <div class="font-semibold mb-2">Desglose por empresa</div>
    <table class="w-full text-sm">
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Horas</th>
                <th>Tarifa</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
            <tr>
                <td>{{ $detalle->empresa }}</td>
                <td>{{ $detalle->horas }}</td>
                <td>{{ $detalle->tarifa_por_hora }}</td>
                <td>{{ $detalle->monto_empresa_divisas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        <div>IVA: <span class="font-bold">{{ $iva_divisas }} ({{ $iva_porcentaje }}%)</span></div>
        <div>ISLR: <span class="font-bold">{{ $islr_divisas }} ({{ $islr_porcentaje }}%)</span></div>
        <div>Total: <span class="font-bold">{{ $total_con_iva_divisas }}</span></div>
    </div>
</div>
