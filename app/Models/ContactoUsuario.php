<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoUsuario extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'contactos_usuario';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'usuario_id',
        'tipo_contacto',
        'valor',
        'es_principal',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'es_principal' => 'boolean',
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
    public function scopeTelefonos($query)
    {
        return $query->where('tipo_contacto', 'telefono');
    }

    public function scopeEmails($query)
    {
        return $query->where('tipo_contacto', 'email');
    }

    public function scopePrincipales($query)
    {
        return $query->where('es_principal', true);
    }

    public function scopeSecundarios($query)
    {
        return $query->where('es_principal', false);
    }

    /**
     * Métodos auxiliares
     */
    public function isTelefono()
    {
        return $this->tipo_contacto === 'telefono';
    }

    public function isEmail()
    {
        return $this->tipo_contacto === 'email';
    }

    public function isPrincipal()
    {
        return $this->es_principal;
    }

    public function marcarComoPrincipal()
    {
        // Desmarcar otros contactos del mismo tipo como principales
        self::where('usuario_id', $this->usuario_id)
            ->where('tipo_contacto', $this->tipo_contacto)
            ->where('id', '!=', $this->id)
            ->update(['es_principal' => false]);

        // Marcar este como principal
        $this->es_principal = true;
        return $this->save();
    }

    public function getTelefonoFormateadoAttribute()
    {
        if ($this->isTelefono()) {
            // Formatear teléfono venezolano
            $telefono = preg_replace('/\D/', '', $this->valor);
            if (strlen($telefono) === 11 && substr($telefono, 0, 4) === '0414') {
                return '+58 ' . substr($telefono, 1, 3) . '-' . substr($telefono, 4, 3) . '-' . substr($telefono, 7, 4);
            }
            return $this->valor;
        }
        return null;
    }

    /**
     * Métodos estáticos
     */
    public static function obtenerTelefonoPrincipal($usuarioId)
    {
        return self::where('usuario_id', $usuarioId)
                   ->telefonos()
                   ->principales()
                   ->first();
    }

    public static function obtenerEmailPrincipal($usuarioId)
    {
        return self::where('usuario_id', $usuarioId)
                   ->emails()
                   ->principales()
                   ->first();
    }

    public static function crearTelefono($usuarioId, $telefono, $esPrincipal = false)
    {
        return self::create([
            'usuario_id' => $usuarioId,
            'tipo_contacto' => 'telefono',
            'valor' => $telefono,
            'es_principal' => $esPrincipal,
        ]);
    }

    public static function crearEmail($usuarioId, $email, $esPrincipal = false)
    {
        return self::create([
            'usuario_id' => $usuarioId,
            'tipo_contacto' => 'email',
            'valor' => $email,
            'es_principal' => $esPrincipal,
        ]);
    }
}