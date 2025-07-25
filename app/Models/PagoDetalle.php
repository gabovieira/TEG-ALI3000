<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoDetalle extends Model
{
    use HasFactory;

    protected $table = 'pago_detalles';

    protected $fillable = [
        'pago_id',
        'empresa_id',
        'horas',
        'tarifa_por_hora',
        'monto_empresa_divisas',
        'subtotal',
        'tipo_moneda',
        'tasa_cambio',
        'subtotal_bs'
    ];

    protected $casts = [
        'horas' => 'decimal:1',
        'tarifa_por_hora' => 'decimal:2',
        'monto_empresa_divisas' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tasa_cambio' => 'decimal:4',
        'subtotal_bs' => 'decimal:2'
    ];

    // Usar timestamps estándar de Laravel
    public $timestamps = true;

    /**
     * Relación con el pago principal
     */
    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }

    /**
     * Relación con la empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class)->withDefault([
            'nombre' => 'Empresa no especificada',
            'rif' => 'N/A'
        ]);
    }

    /**
     * Calcular el subtotal basado en horas y tarifa
     */
    public function calcularSubtotal()
    {
        $this->subtotal = $this->horas * $this->tarifa_por_hora;

        // Si hay tasa de cambio, calcular el monto en bolívares
        if ($this->tipo_moneda === 'USD' && $this->tasa_cambio) {
            $this->subtotal_bs = $this->subtotal * $this->tasa_cambio;
        } else if ($this->tipo_moneda === 'VES') {
            $this->subtotal_bs = $this->subtotal;
        }

        return $this;
    }

    /**
     * Obtener el nombre de la moneda formateado
     */
    public function getMonedaFormateadaAttribute()
    {
        return $this->tipo_moneda === 'USD' ? 'USD $' : 'Bs. ';
    }
}