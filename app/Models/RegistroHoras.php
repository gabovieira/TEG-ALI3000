<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroHoras extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'registro_horas';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'fecha',
        'horas_trabajadas',
        'descripcion_actividades',
        'estado',
        'tipo_registro',
        'aprobado_por',
        'fecha_aprobacion',
        'motivo_rechazo',
        'hora_entrada',
        'hora_salida'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'fecha' => 'date',
        'horas_trabajadas' => 'decimal:1',
        'fecha_aprobacion' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'hora_entrada' => 'datetime',
        'hora_salida' => 'datetime'
    ];

    /**
     * Relaciones
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function aprobador()
    {
        return $this->belongsTo(Usuario::class, 'aprobado_por');
    }

    /**
     * Scopes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopeRechazados($query)
    {
        return $query->where('estado', 'rechazado');
    }

    public function scopeDelMes($query, $mes, $año)
    {
        return $query->whereMonth('fecha', $mes)
                     ->whereYear('fecha', $año);
    }

    public function scopeDelConsultor($query, $consultorId)
    {
        return $query->where('usuario_id', $consultorId);
    }

    public function scopeDeLaEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Métodos auxiliares
     */
    public function isPendiente()
    {
        return $this->estado === 'pendiente';
    }

    public function isAprobado()
    {
        return $this->estado === 'aprobado';
    }

    public function isRechazado()
    {
        return $this->estado === 'rechazado';
    }

    public function aprobar($aprobadorId)
    {
        $this->estado = 'aprobado';
        $this->aprobado_por = $aprobadorId;
        $this->fecha_aprobacion = now();
        return $this->save();
    }

    public function rechazar($aprobadorId, $motivo)
    {
        $this->estado = 'rechazado';
        $this->aprobado_por = $aprobadorId;
        $this->fecha_aprobacion = now();
        $this->motivo_rechazo = $motivo;
        return $this->save();
    }

    public function puedeEditar()
    {
        return $this->isPendiente();
    }

    public function puedeEliminar()
    {
        return $this->isPendiente();
    }
}