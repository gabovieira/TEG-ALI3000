<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TarifaConsultor extends Model
{
    use HasFactory;

    protected $table = 'tarifa_consultores';

    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'tarifa_por_hora',
        'moneda',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'creado_por'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'tarifa_por_hora' => 'decimal:2',
        'activa' => 'boolean'
    ];

    /**
     * Relación con el usuario (consultor)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relación con la empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /**
     * Relación con el usuario que creó la tarifa
     */
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    /**
     * Obtener la tarifa activa para un consultor y empresa
     */
    public static function obtenerTarifaActiva($usuarioId, $empresaId, $fecha = null)
    {
        $fecha = $fecha ?? Carbon::today();
        
        return self::where('usuario_id', $usuarioId)
                   ->where('empresa_id', $empresaId)
                   ->where('activa', true)
                   ->where('fecha_inicio', '<=', $fecha)
                   ->where(function($query) use ($fecha) {
                       $query->whereNull('fecha_fin')
                             ->orWhere('fecha_fin', '>=', $fecha);
                   })
                   ->orderBy('fecha_inicio', 'desc')
                   ->first();
    }

    /**
     * Desactivar tarifa actual y crear nueva
     */
    public static function actualizarTarifa($usuarioId, $empresaId, $nuevaTarifa, $moneda, $fechaInicio, $creadoPor)
    {
        // Desactivar tarifa actual
        self::where('usuario_id', $usuarioId)
            ->where('empresa_id', $empresaId)
            ->where('activa', true)
            ->update([
                'activa' => false,
                'fecha_fin' => Carbon::parse($fechaInicio)->subDay()
            ]);

        // Crear nueva tarifa
        return self::create([
            'usuario_id' => $usuarioId,
            'empresa_id' => $empresaId,
            'tarifa_por_hora' => $nuevaTarifa,
            'moneda' => $moneda,
            'fecha_inicio' => $fechaInicio,
            'activa' => true,
            'creado_por' => $creadoPor
        ]);
    }

    /**
     * Scope para tarifas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para tarifas vigentes en una fecha
     */
    public function scopeVigentesEn($query, $fecha)
    {
        return $query->where('fecha_inicio', '<=', $fecha)
                     ->where(function($q) use ($fecha) {
                         $q->whereNull('fecha_fin')
                           ->orWhere('fecha_fin', '>=', $fecha);
                     });
    }
}