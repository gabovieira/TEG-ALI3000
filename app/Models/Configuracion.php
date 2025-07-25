<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';
    
    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
        'tipo',
        'categoria',
        'actualizado_por'
    ];

    protected $casts = [
        'fecha_actualizacion' => 'datetime',
    ];

    public $timestamps = false;

    // Relación con el usuario que actualizó la configuración
    public function actualizadoPor()
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    // Método para obtener el valor según su tipo
    public function getValorFormateadoAttribute()
    {
        switch ($this->tipo) {
            case 'numero':
                return (float) $this->valor;
            case 'booleano':
                return (bool) $this->valor;
            case 'json':
                return json_decode($this->valor, true);
            default:
                return $this->valor;
        }
    }

    // Método estático para obtener una configuración por clave
    public static function obtener($clave, $default = null)
    {
        $config = static::where('clave', $clave)->first();
        return $config ? $config->valor_formateado : $default;
    }

    // Método estático para actualizar una configuración
    public static function actualizar($clave, $valor, $usuarioId = null)
    {
        return static::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'actualizado_por' => $usuarioId,
                'fecha_actualizacion' => now()
            ]
        );
    }

    // Scopes para filtrar por categoría
    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopeImpuestos($query)
    {
        return $query->where('categoria', 'impuestos');
    }

    public function scopeHoras($query)
    {
        return $query->where('categoria', 'horas');
    }

    public function scopeValidacion($query)
    {
        return $query->where('categoria', 'validacion');
    }

    public function scopeNotificaciones($query)
    {
        return $query->where('categoria', 'notificaciones');
    }

    public function scopeEmpresa($query)
    {
        return $query->where('categoria', 'empresa');
    }

    public function scopeApi($query)
    {
        return $query->where('categoria', 'api');
    }

    public function scopePagos($query)
    {
        return $query->where('categoria', 'pagos');
    }
}