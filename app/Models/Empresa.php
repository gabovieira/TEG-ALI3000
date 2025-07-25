<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'empresas';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre',
        'rif',
        'tipo_empresa',
        'direccion',
        'telefono',
        'email',
        'estado',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    /**
     * Scopes
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    public function scopeInactivas($query)
    {
        return $query->where('estado', 'inactiva');
    }

    /**
     * Relaciones
     */
    public function consultores()
    {
        return $this->belongsToMany(Usuario::class, 'empresa_consultores', 'empresa_id', 'usuario_id')
                    ->withPivot('fecha_asignacion', 'tipo_asignacion', 'estado', 'observaciones');
    }

    public function registrosHoras()
    {
        return $this->hasMany(RegistroHoras::class, 'empresa_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'empresa_id');
    }

    /**
     * Métodos auxiliares
     */
    public function isActiva()
    {
        return $this->estado === 'activa';
    }

    public function getTotalPagosAttribute()
    {
        return $this->pagos()->where('estado', 'pagado')->sum('total_menos_islr_bs');
    }

    public function getConsultoresActivosAttribute()
    {
        return $this->consultores()->wherePivot('estado', 'activo')->count();
    }
}