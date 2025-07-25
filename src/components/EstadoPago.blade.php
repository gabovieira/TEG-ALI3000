{{-- EstadoPago: Badge de estado con colores y tooltip --}}
@php
    $color = match($estado) {
        'pendiente' => 'bg-yellow-100 text-yellow-800',
        'pagado' => 'bg-green-100 text-green-800',
        'anulado' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800',
    };
@endphp
<span class="px-2 py-1 rounded {{ $color }}" title="{{ ucfirst($estado) }}">{{ ucfirst($estado) }}</span>
