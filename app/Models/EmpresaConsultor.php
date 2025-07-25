<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaConsultor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'empresa_consultores';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empresa_id',
        'usuario_id',
        'fecha_asignacion',
        'tipo_asignacion',
        'estado',
        'observaciones',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'fecha_asignacion' => 'date',
    ];

    /**
     * Relaciones
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function consultor()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Scopes
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeInactivos($query)
    {
        return $query->where('estado', 'inactivo');
    }

    public function scopeTiempoCompleto($query)
    {
        return $query->where('tipo_asignacion', 'tiempo_completo');
    }

    public function scopeParcial($query)
    {
        return $query->where('tipo_asignacion', 'parcial');
    }

    public function scopeTemporal($query)
    {
        return $query->where('tipo_asignacion', 'temporal');
    }

    public function scopeAsignadosEn($query, $fecha)
    {
        return $query->where('fecha_asignacion', '<=', $fecha);
    }

    /**
     * Métodos auxiliares
     */
    public function isActivo()
    {
        return $this->estado === 'activo';
    }

    public function isInactivo()
    {
        return $this->estado === 'inactivo';
    }

    public function isTiempoCompleto()
    {
        return $this->tipo_asignacion === 'tiempo_completo';
    }

    public function isParcial()
    {
        return $this->tipo_asignacion === 'parcial';
    }

    public function isTemporal()
    {
        return $this->tipo_asignacion === 'temporal';
    }

    public function activar()
    {
        $this->estado = 'activo';
        return $this->save();
    }

    public function desactivar($observacion = null)
    {
        $this->estado = 'inactivo';
        if ($observacion) {
            $this->observaciones = $observacion;
        }
        return $this->save();
    }

    public function cambiarTipoAsignacion($nuevoTipo, $observacion = null)
    {
        $tiposValidos = ['tiempo_completo', 'parcial', 'temporal'];
        
        if (!in_array($nuevoTipo, $tiposValidos)) {
            throw new \InvalidArgumentException('Tipo de asignación no válido');
        }

        $this->tipo_asignacion = $nuevoTipo;
        if ($observacion) {
            $this->observaciones = $observacion;
        }
        
        return $this->save();
    }

    public function getDiasAsignadoAttribute()
    {
        return $this->fecha_asignacion->diffInDays(now());
    }

    public function getFechaAsignacionFormateadaAttribute()
    {
        return $this->fecha_asignacion->format('d/m/Y');
    }

    /**
     * Métodos estáticos
     */
    public static function asignarConsultor($empresaId, $usuarioId, $tipoAsignacion = 'tiempo_completo', $observaciones = null)
    {
        return self::create([
            'empresa_id' => $empresaId,
            'usuario_id' => $usuarioId,
            'fecha_asignacion' => now()->toDateString(),
            'tipo_asignacion' => $tipoAsignacion,
            'estado' => 'activo',
            'observaciones' => $observaciones,
        ]);
    }

    public static function obtenerConsultoresDeEmpresa($empresaId, $soloActivos = true)
    {
        $query = self::where('empresa_id', $empresaId);
        
        if ($soloActivos) {
            $query->activos();
        }
        
        return $query->with('usuario')->get();
    }

    public static function obtenerEmpresasDeConsultor($usuarioId, $soloActivas = true)
    {
        $query = self::where('usuario_id', $usuarioId);
        
        if ($soloActivas) {
            $query->activos();
        }
        
        return $query->with('empresa')->get();
    }

    public static function consultorEstaAsignado($empresaId, $usuarioId)
    {
        return self::where('empresa_id', $empresaId)
                   ->where('usuario_id', $usuarioId)
                   ->activos()
                   ->exists();
    }
}