@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Detalle de Pago</h1>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        @include('components.PagoCard', [
            'avatar' => $pago->consultor->avatar ?? null,
            'nombre' => $pago->consultor->nombre,
            'empresa' => $pago->empresa->nombre ?? 'Múltiples',
            'monto' => $pago->total_con_iva_divisas,
            'estado' => $pago->estado
        ])
        @include('components.CalculoDetalle', [
            'detalles' => $pago->detalles,
            'iva_divisas' => $pago->iva_divisas,
            'iva_porcentaje' => $pago->iva_porcentaje,
            'islr_divisas' => $pago->islr_divisas,
            'islr_porcentaje' => $pago->islr_porcentaje,
            'total_con_iva_divisas' => $pago->total_con_iva_divisas
        ])
        <div class="mt-4">
            <div class="font-semibold">Historial de Estado</div>
            <ul class="list-disc ml-4 text-sm">
                @foreach($pago->historial as $h)
                    <li>{{ $h->fecha }} - {{ ucfirst($h->estado) }} por {{ $h->usuario->nombre }}</li>
                @endforeach
            </ul>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.pagos.index') }}" class="btn btn-outline">Volver</a>
        </div>
    </div>
</div>
@endsection
