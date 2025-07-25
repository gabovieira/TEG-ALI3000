<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TokenRegistro extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'tokens_registro';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'usuario_id',
        'token',
        'fecha_expiracion',
        'usado',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_expiracion' => 'datetime',
        'usado' => 'boolean',
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
    public function scopeValidos($query)
    {
        return $query->where('usado', false)
                     ->where('fecha_expiracion', '>', now());
    }

    public function scopeExpirados($query)
    {
        return $query->where('fecha_expiracion', '<=', now());
    }

    public function scopeUsados($query)
    {
        return $query->where('usado', true);
    }

    public function scopeNoUsados($query)
    {
        return $query->where('usado', false);
    }

    /**
     * Métodos auxiliares
     */
    public function isValido()
    {
        return !$this->usado && $this->fecha_expiracion > now();
    }

    public function isExpirado()
    {
        return $this->fecha_expiracion <= now();
    }

    public function isUsado()
    {
        return $this->usado;
    }

    public function marcarComoUsado()
    {
        $this->usado = true;
        return $this->save();
    }

    public function extenderExpiracion($dias = 7)
    {
        // Asegurar que dias sea un entero
        $dias = (int) $dias;
        $this->fecha_expiracion = now()->copy()->addDays($dias);
        return $this->save();
    }

    public function getDiasParaExpirarAttribute()
    {
        if ($this->isExpirado()) {
            return 0;
        }
        
        return now()->diffInDays($this->fecha_expiracion);
    }

    public function getHorasParaExpirarAttribute()
    {
        if ($this->isExpirado()) {
            return 0;
        }
        
        return now()->diffInHours($this->fecha_expiracion);
    }

    public function getUrlRegistroAttribute()
    {
        return route('registro.formulario', ['token' => $this->token]);
    }

    /**
     * Métodos estáticos
     */
    public static function generarToken($usuarioId, $diasExpiracion = 7)
    {
        // Invalidar tokens anteriores del mismo usuario
        self::where('usuario_id', $usuarioId)
            ->where('usado', false)
            ->update(['usado' => true]);

        // Asegurar que diasExpiracion sea un entero
        $diasExpiracion = intval($diasExpiracion);

        // Crear nuevo token
        return self::create([
            'usuario_id' => $usuarioId,
            'token' => self::generarTokenUnico(),
            'fecha_expiracion' => now()->copy()->addDays($diasExpiracion),
            'usado' => false,
        ]);
    }

    public static function buscarPorToken($token)
    {
        return self::where('token', $token)->first();
    }

    public static function validarToken($token)
    {
        $tokenRegistro = self::buscarPorToken($token);
        
        if (!$tokenRegistro) {
            return ['valido' => false, 'mensaje' => 'Token no encontrado'];
        }

        if ($tokenRegistro->isUsado()) {
            return ['valido' => false, 'mensaje' => 'Token ya utilizado'];
        }

        if ($tokenRegistro->isExpirado()) {
            return ['valido' => false, 'mensaje' => 'Token expirado'];
        }

        return ['valido' => true, 'token' => $tokenRegistro];
    }

    public static function limpiarTokensExpirados()
    {
        return self::expirados()->delete();
    }

    public static function obtenerTokensProximosAExpirar($dias = 1)
    {
        // Asegurar que dias sea un entero
        $dias = (int) $dias;
        $fechaLimite = now()->copy()->addDays($dias);
        
        return self::validos()
                   ->where('fecha_expiracion', '<=', $fechaLimite)
                   ->with('usuario')
                   ->get();
    }

    /**
     * Métodos privados
     */
    private static function generarTokenUnico()
    {
        do {
            $token = Str::random(64);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Eventos del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = self::generarTokenUnico();
            }
            
            if (empty($model->fecha_expiracion)) {
                $model->fecha_expiracion = now()->copy()->addDays((int) 7);
            }
        });
    }
}