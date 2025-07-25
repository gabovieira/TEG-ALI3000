<?php
namespace App\Services;
use App\Models\TasaBCV;
class TasaBcvService {
    public static function getTasaActual() {
        // Simulación de tasa BCV
        return (object)[
            'valor' => '119.6700',
            'valor_bs' => '119.6700',
            'fecha' => '22/07/2025'
        ];
    }
}
