<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DatosBancario extends Model
{
    use HasFactory;

    protected $table = 'datos_bancarios';

    protected $fillable = [
        'usuario_id',
        'banco',
        'tipo_cuenta',
        'numero_cuenta',
        'cedula_rif',
        'titular',
        'es_principal'
        // Solo estos campos existen en la tabla según la estructura proporcionada
        // No incluir: correo, telefono, observaciones, estado
    ];
    
    protected $casts = [
        'es_principal' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime'
    ];
    
    // Usar timestamps estándar de Laravel
    public $timestamps = true;
    
    // Estados posibles
    const ESTADO_ACTIVO = 'activo';
    const ESTADO_INACTIVO = 'inactivo';

    /**
     * Obtiene el usuario al que pertenecen estos datos bancarios.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class)->withDefault([
            'primer_nombre' => 'N/A',
            'primer_apellido' => ''
        ]);
    }

    /**
     * Obtiene los pagos asociados a estos datos bancarios.
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'datos_bancarios_id')
            ->orderBy('fecha_pago', 'desc');
    }
    
    /**
     * Scope para obtener solo cuentas activas
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }
    
    /**
     * Scope para obtener solo cuentas principales
     */
    public function scopePrincipales($query)
    {
        return $query->where('es_principal', true);
    }
    
    /**
     * Marcar como cuenta principal
     */
    public function marcarComoPrincipal()
    {
        // Primero desmarcar cualquier otra cuenta principal del usuario
        self::where('usuario_id', $this->usuario_id)
            ->where('id', '!=', $this->id)
            ->update(['es_principal' => false]);
            
        $this->es_principal = true;
        return $this->save();
    }
    
    /**
     * Obtener la información del banco formateada
     */
    public function getInformacionCompletaAttribute()
    {
        return sprintf(
            "%s - %s %s (%s)",
            $this->banco,
            $this->tipo_cuenta,
            $this->numero_cuenta,
            $this->titular
        );
    }
    
    /**
     * Obtener el tipo de cuenta formateado
     */
    public function getTipoCuentaFormateadoAttribute()
    {
        $tipos = [
            'ahorro' => 'Cuenta de Ahorro',
            'corriente' => 'Cuenta Corriente',
            'pago_movil' => 'Pago Móvil',
            'pago_efectivo' => 'Pago en Efectivo',
            'transferencia' => 'Transferencia',
            'punto_venta' => 'Punto de Venta',
            'zelle' => 'Zelle',
            'paypal' => 'PayPal',
            'binance' => 'Binance',
            'airtm' => 'Airtm',
            'usdt' => 'USDT',
            'btc' => 'Bitcoin',
            'eth' => 'Ethereum',
            'otro' => 'Otro',
        ];
        
        return $tipos[$this->tipo_cuenta] ?? ucfirst($this->tipo_cuenta);
    }

    /**
     * Valida el formato del número de cuenta según el banco.
     *
     * @return bool
     */
    public function validarNumeroCuenta()
    {
        // Implementar validación específica según el banco
        switch ($this->banco) {
            case 'Banco de Venezuela':
                // Ejemplo: 20 dígitos numéricos
                return preg_match('/^\d{20}$/', $this->numero_cuenta);
            case 'Banesco':
                // Ejemplo: 20 dígitos numéricos
                return preg_match('/^\d{20}$/', $this->numero_cuenta);
            case 'Mercantil':
                // Ejemplo: 20 dígitos numéricos
                return preg_match('/^\d{20}$/', $this->numero_cuenta);
            default:
                // Validación genérica: entre 15 y 20 dígitos numéricos
                return preg_match('/^\d{15,20}$/', $this->numero_cuenta);
        }
    }

    /**
     * Establece este registro como la cuenta principal y desmarca las demás.
     */
    public function establecerComoPrincipal()
    {
        // Desmarcar todas las cuentas del usuario como no principales
        self::where('usuario_id', $this->usuario_id)
            ->where('id', '!=', $this->id)
            ->update(['es_principal' => false]);

        // Marcar esta cuenta como principal
        $this->es_principal = true;
        $this->save();
    }
}
