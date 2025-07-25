@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Gestión de Pagos</h1>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        @include('components.FiltrosPago', [
            'consultores' => $consultores,
            'empresas' => $empresas
        ])
        <table class="w-full table-auto text-sm">
            <thead>
                <tr>
                    <th>Consultor</th>
                    <th>Empresa</th>
                    <th>Quincena</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagos as $pago)
                <tr class="border-b">
                    <td>{{ $pago->consultor->nombre }}</td>
                    <td>{{ $pago->empresa->nombre ?? 'Múltiples' }}</td>
                    <td>{{ $pago->quincena }}</td>
                    <td>@include('components.MontoDisplay', [ 'monto_divisas' => $pago->total_con_iva_divisas, 'monto_bs' => $pago->total_con_iva_bs ])</td>
                    <td>@include('components.EstadoPago', [ 'estado' => $pago->estado ])</td>
                    <td>
                        <a href="{{ route('admin.pagos.show', $pago->id) }}" class="btn btn-sm btn-outline">Ver detalle</a>
                        @if($pago->estado == 'pendiente')
                            <a href="{{ route('admin.pagos.marcarPagado', $pago->id) }}" class="btn btn-sm btn-success">Marcar pagado</a>
                            <a href="{{ route('admin.pagos.anular', $pago->id) }}" class="btn btn-sm btn-danger">Anular</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $pagos->links() }}</div>
    </div>
</div>
@endsection
