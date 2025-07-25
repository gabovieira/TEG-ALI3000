<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'notificaciones';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'usuario_id',
        'tipo',
        'tipo_notificacion',
        'asunto',
        'mensaje',
        'estado',
        'datos_extra',
        'fecha_programada',
        'fecha_enviada',
        'intentos',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'datos_extra' => 'array',
        'fecha_programada' => 'datetime',
        'fecha_enviada' => 'datetime',
        'fecha_creacion' => 'datetime',
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
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnviadas($query)
    {
        return $query->where('estado', 'enviada');
    }

    public function scopeFallidas($query)
    {
        return $query->where('estado', 'fallida');
    }

    public function scopeLeidas($query)
    {
        return $query->where('estado', 'leida');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorTipoNotificacion($query, $tipoNotificacion)
    {
        return $query->where('tipo_notificacion', $tipoNotificacion);
    }

    public function scopeProgramadasPara($query, $fecha = null)
    {
        $fecha = $fecha ?? now();
        return $query->where('fecha_programada', '<=', $fecha);
    }

    /**
     * Métodos auxiliares
     */
    public function isPendiente()
    {
        return $this->estado === 'pendiente';
    }

    public function isEnviada()
    {
        return $this->estado === 'enviada';
    }

    public function isFallida()
    {
        return $this->estado === 'fallida';
    }

    public function isLeida()
    {
        return $this->estado === 'leida';
    }

    public function marcarComoEnviada()
    {
        $this->estado = 'enviada';
        $this->fecha_enviada = now();
        return $this->save();
    }

    public function marcarComoFallida()
    {
        $this->estado = 'fallida';
        $this->intentos++;
        return $this->save();
    }

    public function marcarComoLeida()
    {
        $this->estado = 'leida';
        return $this->save();
    }

    public function puedeReintentar($maxIntentos = 3)
    {
        return $this->intentos < $maxIntentos;
    }

    /**
     * Métodos estáticos para crear notificaciones
     */
    public static function crearNotificacionPago($usuarioId, $pagoId, $monto)
    {
        return self::create([
            'usuario_id' => $usuarioId,
            'tipo' => 'sistema',
            'tipo_notificacion' => 'pago_procesado',
            'asunto' => 'Pago Procesado',
            'mensaje' => "Su pago por {$monto} ha sido procesado exitosamente.",
            'datos_extra' => ['pago_id' => $pagoId],
            'fecha_programada' => now(),
        ]);
    }

    public static function crearNotificacionHorasAprobadas($usuarioId, $registroHorasId, $horas)
    {
        return self::create([
            'usuario_id' => $usuarioId,
            'tipo' => 'sistema',
            'tipo_notificacion' => 'horas_aprobadas',
            'asunto' => 'Horas Aprobadas',
            'mensaje' => "Sus {$horas} horas han sido aprobadas.",
            'datos_extra' => ['registro_horas_id' => $registroHorasId],
            'fecha_programada' => now(),
        ]);
    }

    public static function crearNotificacionHorasRechazadas($usuarioId, $registroHorasId, $motivo)
    {
        return self::create([
            'usuario_id' => $usuarioId,
            'tipo' => 'sistema',
            'tipo_notificacion' => 'horas_rechazadas',
            'asunto' => 'Horas Rechazadas',
            'mensaje' => "Sus horas han sido rechazadas. Motivo: {$motivo}",
            'datos_extra' => ['registro_horas_id' => $registroHorasId],
            'fecha_programada' => now(),
        ]);
    }
}