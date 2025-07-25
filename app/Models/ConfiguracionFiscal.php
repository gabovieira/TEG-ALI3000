<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ConfiguracionFiscal extends Model
{
    use HasFactory;

    protected $table = 'configuracion_fiscal';

    protected $fillable = [
        'iva_porcentaje',
        'islr_porcentaje',
        'fecha_vigencia',
        'activa',
        'creado_por'
    ];

    protected $casts = [
        'iva_porcentaje' => 'decimal:2',
        'islr_porcentaje' => 'decimal:2',
        'fecha_vigencia' => 'date',
        'activa' => 'boolean'
    ];

    /**
     * Relación con el usuario que creó la configuración
     */
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    /**
     * Obtener la configuración fiscal activa
     */
    public static function obtenerActual()
    {
        return self::where('activa', true)->first();
    }

    /**
     * Obtener configuración vigente para una fecha específica
     */
    public static function obtenerPorFecha($fecha)
    {
        return self::where('fecha_vigencia', '<=', $fecha)
                   ->orderBy('fecha_vigencia', 'desc')
                   ->first();
    }

    /**
     * Crear nueva configuración y desactivar la anterior
     */
    public static function actualizarConfiguracion($iva, $islr, $fechaVigencia, $creadoPor)
    {
        // Desactivar configuración actual
        self::where('activa', true)->update(['activa' => false]);

        // Crear nueva configuración
        return self::create([
            'iva_porcentaje' => $iva,
            'islr_porcentaje' => $islr,
            'fecha_vigencia' => $fechaVigencia,
            'activa' => true,
            'creado_por' => $creadoPor
        ]);
    }

    /**
     * Obtener historial de configuraciones
     */
    public static function obtenerHistorial()
    {
        return self::with('creador')
                   ->orderBy('fecha_vigencia', 'desc')
                   ->get();
    }

    /**
     * Scope para configuraciones activas
     */
    public function scopeActiva($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Calcular IVA sobre un monto
     */
    public function calcularIva($monto)
    {
        return $monto * ($this->iva_porcentaje / 100);
    }

    /**
     * Calcular ISLR sobre un monto
     */
    public function calcularIslr($monto)
    {
        return $monto * ($this->islr_porcentaje / 100);
    }
}