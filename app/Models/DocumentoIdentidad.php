<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoIdentidad extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'documentos_identidad';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'usuario_id',
        'tipo_documento',
        'numero',
        'digito_verificador',
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
    public function scopeCedulas($query)
    {
        return $query->where('tipo_documento', 'cedula');
    }

    public function scopeRifs($query)
    {
        return $query->where('tipo_documento', 'rif');
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
    public function isCedula()
    {
        return $this->tipo_documento === 'cedula';
    }

    public function isRif()
    {
        return $this->tipo_documento === 'rif';
    }

    public function isPrincipal()
    {
        return $this->es_principal;
    }

    public function marcarComoPrincipal()
    {
        // Desmarcar otros documentos del mismo tipo como principales
        self::where('usuario_id', $this->usuario_id)
            ->where('tipo_documento', $this->tipo_documento)
            ->where('id', '!=', $this->id)
            ->update(['es_principal' => false]);

        // Marcar este como principal
        $this->es_principal = true;
        return $this->save();
    }

    public function getNumeroCompletoAttribute()
    {
        if ($this->digito_verificador) {
            return $this->numero . '-' . $this->digito_verificador;
        }
        return $this->numero;
    }

    public function getNumeroFormateadoAttribute()
    {
        if ($this->isCedula()) {
            // Formatear cédula venezolana (V-12345678)
            $letra = substr($this->numero, 0, 1);
            $numeros = (int) substr($this->numero, 1);
            return $letra . '-' . number_format($numeros, 0, '', '.');
        } elseif ($this->isRif()) {
            // Formatear RIF venezolano (J-12345678-9)
            $letra = substr($this->numero, 0, 1);
            $numeros = (int) substr($this->numero, 1);
            return $letra . '-' . number_format($numeros, 0, '', '.') . 
                   ($this->digito_verificador ? '-' . $this->digito_verificador : '');
        }
        
        return $this->getNumeroCompletoAttribute();
    }

    /**
     * Validaciones
     */
    public function validarCedula()
    {
        if (!$this->isCedula()) {
            return false;
        }

        // Validar que sea un número válido
        if (!is_numeric($this->numero) || $this->numero <= 0) {
            return false;
        }

        // Validar longitud (entre 6 y 8 dígitos)
        $longitud = strlen($this->numero);
        return $longitud >= 6 && $longitud <= 8;
    }

    public function validarRif()
    {
        if (!$this->isRif()) {
            return false;
        }

        // El RIF debe tener al menos 9 caracteres (letra + 8 números)
        if (strlen($this->numero) < 9) {
            return false;
        }

        // Debe comenzar con una letra válida
        $primeraLetra = strtoupper(substr($this->numero, 0, 1));
        $letrasValidas = ['V', 'E', 'J', 'P', 'G'];
        
        if (!in_array($primeraLetra, $letrasValidas)) {
            return false;
        }

        // Los siguientes 8 caracteres deben ser números
        $numeros = substr($this->numero, 1, 8);
        return is_numeric($numeros);
    }

    /**
     * Métodos estáticos
     */
    public static function obtenerCedulaPrincipal($usuarioId)
    {
        return self::where('usuario_id', $usuarioId)
                   ->cedulas()
                   ->principales()
                   ->first();
    }

    public static function obtenerRifPrincipal($usuarioId)
    {
        return self::where('usuario_id', $usuarioId)
                   ->rifs()
                   ->principales()
                   ->first();
    }

    public static function crearCedula($usuarioId, $numero, $esPrincipal = false)
    {
        return self::create([
            'usuario_id' => $usuarioId,
            'tipo_documento' => 'cedula',
            'numero' => $numero,
            'es_principal' => $esPrincipal,
        ]);
    }

    public static function crearRif($usuarioId, $numero, $digitoVerificador = null, $esPrincipal = false)
    {
        return self::create([
            'usuario_id' => $usuarioId,
            'tipo_documento' => 'rif',
            'numero' => $numero,
            'digito_verificador' => $digitoVerificador,
            'es_principal' => $esPrincipal,
        ]);
    }
}