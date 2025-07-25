<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosLaborales extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'datos_laborales';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'usuario_id',
        'tarifa_por_hora',
        'nivel_desarrollo',
        'telefono_personal',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tarifa_por_hora' => 'decimal:2',
    ];

    /**
     * Relaciones
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Scopes
     */
    public function scopeJunior($query)
    {
        return $query->where('nivel_desarrollo', 'junior');
    }

    public function scopeSemiSenior($query)
    {
        return $query->where('nivel_desarrollo', 'semi-senior');
    }

    public function scopeSenior($query)
    {
        return $query->where('nivel_desarrollo', 'senior');
    }

    /**
     * Métodos auxiliares
     */
    public function getTarifaFormateadaAttribute()
    {
        return '$' . number_format($this->tarifa_por_hora, 2);
    }

    public function isJunior()
    {
        return $this->nivel_desarrollo === 'junior';
    }

    public function isSemiSenior()
    {
        return $this->nivel_desarrollo === 'semi-senior';
    }

    public function isSenior()
    {
        return $this->nivel_desarrollo === 'senior';
    }
}