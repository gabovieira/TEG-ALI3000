<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

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
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipo_usuario',
        'primer_nombre',
        'primer_apellido',
        'email',
        'password_hash',
        'estado',
        'creado_por',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    /**
     * Get the password attribute name for authentication.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Scope para filtrar por tipo de usuario
     */
    public function scopeAdmins($query)
    {
        return $query->where('tipo_usuario', 'admin');
    }

    public function scopeConsultores($query)
    {
        return $query->where('tipo_usuario', 'consultor');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Relaciones
     */
    public function datosLaborales()
    {
        return $this->hasOne(DatosLaborales::class, 'usuario_id');
    }

    public function contactos()
    {
        return $this->hasMany(ContactoUsuario::class, 'usuario_id');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoIdentidad::class, 'usuario_id');
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_consultores', 'usuario_id', 'empresa_id')
                    ->withPivot('fecha_asignacion', 'tipo_asignacion', 'estado', 'observaciones');
    }

    public function registrosHoras()
    {
        return $this->hasMany(RegistroHoras::class, 'usuario_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'usuario_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }
    
    public function datosBancarios()
    {
        return $this->hasMany(DatosBancario::class, 'usuario_id');
    }
    
    public function datosBancarioPrincipal()
    {
        return $this->hasOne(DatosBancario::class, 'usuario_id')->where('es_principal', true);
    }

    /**
     * Métodos auxiliares
     */
    public function getNombreCompletoAttribute()
    {
        return $this->primer_nombre . ' ' . $this->primer_apellido;
    }

    public function isAdmin()
    {
        return $this->tipo_usuario === 'admin';
    }

    public function isConsultor()
    {
        return $this->tipo_usuario === 'consultor';
    }

    public function isActivo()
    {
        return $this->estado === 'activo';
    }
}