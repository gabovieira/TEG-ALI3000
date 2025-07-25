<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feriado extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feriados';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fecha',
        'descripcion',
        'tipo',
        'activo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha' => 'date',
        'activo' => 'boolean',
    ];

    /**
     * Scope para obtener feriados de un mes específico
     */
    public function scopeDelMes($query, $year, $month)
    {
        return $query->whereYear('fecha', $year)
                    ->whereMonth('fecha', $month)
                    ->where('activo', true);
    }

    /**
     * Scope para obtener solo feriados bancarios
     */
    public function scopeBancarios($query)
    {
        return $query->where('tipo', 'bancario');
    }

    /**
     * Scope para obtener feriados por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para obtener solo feriados activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}