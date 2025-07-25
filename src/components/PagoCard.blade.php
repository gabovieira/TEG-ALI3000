{{-- PagoCard: Tarjeta resumen de pago --}}
<div class="flex items-center p-4 bg-white rounded-lg shadow mb-2">
    <img src="{{ $avatar ?? '/assets/img/avatar-default.png' }}" alt="Avatar" class="w-10 h-10 rounded-full mr-4">
    <div class="flex-1">
        <div class="font-semibold text-base">{{ $nombre }}</div>
        <div class="text-sm text-gray-500">{{ $empresa }}</div>
    </div>
    <div class="text-right">
        @include('components.MontoDisplay', [ 'monto_divisas' => $monto, 'monto_bs' => $monto_bs ?? null ])
        @include('components.EstadoPago', [ 'estado' => $estado ])
    </div>
</div>
