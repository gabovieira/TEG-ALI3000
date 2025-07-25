<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    
    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'quincena',
        'horas',
        'tarifa_por_hora',
        'monto_total',
        'iva_porcentaje',
        'iva_monto',
        'islr_porcentaje',
        'islr_monto',
        'monto_neto',
        'ingreso_divisas',
        'monto_base_divisas',
        'iva_divisas',
        'total_con_iva_divisas',
        'tasa_cambio',
        'fecha_tasa_bcv',
        'monto_base_bs',
        'iva_bs',
        'total_con_iva_bs',
        'islr_divisas',
        'total_menos_islr_divisas',
        'islr_bs',
        'total_menos_islr_bs',
        'referencia_bancaria',
        'fecha_pago',
        'estado', // pendiente, pagado, confirmado, rechazado, anulado
        'observaciones',
        'procesado_por',
        'fecha_confirmacion',
        'comentario_confirmacion',
        'comprobante_pago',
        'datos_bancarios_id'
    ];

    protected $casts = [
        'fecha_tasa_bcv' => 'date',
        'fecha_pago' => 'date',
        'fecha_confirmacion' => 'datetime',
        'horas' => 'decimal:1',
        'tarifa_por_hora' => 'decimal:2',
        'monto_total' => 'decimal:2',
        'iva_porcentaje' => 'decimal:2',
        'iva_monto' => 'decimal:2',
        'islr_porcentaje' => 'decimal:2',
        'islr_monto' => 'decimal:2',
        'monto_neto' => 'decimal:2',
        'ingreso_divisas' => 'decimal:2',
        'monto_base_divisas' => 'decimal:2',
        'iva_divisas' => 'decimal:2',
        'total_con_iva_divisas' => 'decimal:2',
        'islr_divisas' => 'decimal:2',
        'total_menos_islr_divisas' => 'decimal:2',
        'monto_base_bs' => 'decimal:2',
        'iva_bs' => 'decimal:2',
        'total_con_iva_bs' => 'decimal:2',
        'islr_bs' => 'decimal:2',
        'total_menos_islr_bs' => 'decimal:2',
        'tasa_cambio' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Usar timestamps estándar de Laravel
    public $timestamps = true;

    /**
     * Relación con el usuario (consultor)
     */
    public function consultor()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id')->withDefault([
            'primer_nombre' => 'N/A',
            'primer_apellido' => ''
        ]);
    }
    
    /**
     * Alias para la relación consultor (para compatibilidad)
     */
    public function usuario()
    {
        return $this->consultor();
    }

    /**
     * Relación con los detalles del pago por empresa
     */
    public function detalles()
    {
        return $this->hasMany(PagoDetalle::class)->with('empresa');
    }

    /**
     * Relación con el usuario que procesó el pago
     */
    public function procesador()
    {
        return $this->belongsTo(Usuario::class, 'procesado_por')->withDefault([
            'primer_nombre' => 'Sistema',
            'primer_apellido' => ''
        ]);
    }
    
    /**
     * Relación con los datos bancarios utilizados para el pago
     */
    public function datosBancarios()
    {
        return $this->belongsTo(DatosBancario::class, 'datos_bancarios_id')->withDefault([
            'banco' => 'No especificado',
            'tipo_cuenta' => 'No especificado',
            'numero_cuenta' => 'No especificado'
        ]);
    }

    /**
     * Scope para obtener pagos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para obtener pagos pagados
     */
    public function scopePagados($query)
    {
        return $query->where('estado', 'pagado');
    }

    /**
     * Scope para obtener pagos confirmados
     */
    public function scopeConfirmados($query)
    {
        return $query->where('estado', 'confirmado');
    }

    /**
     * Scope para obtener pagos por quincena
     */
    public function scopePorQuincena($query, $quincena)
    {
        return $query->where('quincena', $quincena);
    }

    /**
     * Scope para obtener pagos por consultor
     */
    public function scopePorConsultor($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    /**
     * Calcular el monto total del pago
     */
    public function calcularMontos()
    {
        $this->monto_total = $this->detalles->sum('subtotal');
        $this->iva_monto = $this->monto_total * ($this->iva_porcentaje / 100);
        $this->islr_monto = $this->monto_total * ($this->islr_porcentaje / 100);
        $this->monto_neto = $this->monto_total + $this->iva_monto - $this->islr_monto;
        return $this;
    }

    /**
     * Obtener el nombre completo del consultor
     */
    public function getConsultorNombreCompletoAttribute()
    {
        return $this->consultor->primer_nombre . ' ' . $this->consultor->primer_apellido;
    }
    
    /**
     * Accessor para total_horas (compatibilidad con vistas)
     */
    public function getTotalHorasAttribute()
    {
        return $this->horas;
    }

    /**
     * Obtener los registros de horas asociados a este pago
     */
    public function registrosHoras()
    {
        // Obtener fechas de inicio y fin de la quincena
        list($fechaInicio, $fechaFin) = $this->obtenerFechasQuincena();
        
        return RegistroHoras::where('usuario_id', $this->usuario_id)
                          ->where('estado', 'aprobado')
                          ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                          ->get();
    }

    /**
     * Obtener las fechas de inicio y fin de la quincena
     */
    public function obtenerFechasQuincena()
    {
        // Formato esperado de quincena: "YYYY-MM-Q" (ej: "2025-07-1" para primera quincena de julio 2025)
        $partes = explode('-', $this->quincena);
        
        if (count($partes) != 3) {
            throw new \Exception("Formato de quincena inválido: {$this->quincena}");
        }
        
        $anio = (int)$partes[0];
        $mes = (int)$partes[1];
        $quincena = (int)$partes[2];
        
        if ($quincena == 1) {
            // Primera quincena: del 1 al 15
            $fechaInicio = Carbon::create($anio, $mes, 1)->startOfDay();
            $fechaFin = Carbon::create($anio, $mes, 15)->endOfDay();
        } else {
            // Segunda quincena: del 16 al último día del mes
            $fechaInicio = Carbon::create($anio, $mes, 16)->startOfDay();
            $fechaFin = Carbon::create($anio, $mes)->endOfMonth()->endOfDay();
        }
        
        return [$fechaInicio, $fechaFin];
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        if ($estado) {
            return $query->where('estado', $estado);
        }
        return $query;
    }

    /**
     * Scope para filtrar por consultor
     */
    public function scopeConsultor($query, $consultorId)
    {
        if ($consultorId) {
            return $query->where('usuario_id', $consultorId);
        }
        return $query;
    }

    /**
     * Scope para filtrar por empresa (a través de los detalles)
     */
    public function scopeEmpresa($query, $empresaId)
    {
        if ($empresaId) {
            return $query->whereHas('detalles', function($q) use ($empresaId) {
                $q->where('empresa_id', $empresaId);
            });
        }
        return $query;
    }

    /**
     * Scope para filtrar por quincena
     */
    public function scopeQuincena($query, $quincena)
    {
        if ($quincena) {
            return $query->where('quincena', $quincena);
        }
        return $query;
    }

    /**
     * Scope para filtrar por rango de fechas de pago
     */
    public function scopeFechaPago($query, $fechaInicio, $fechaFin)
    {
        if ($fechaInicio) {
            $query->where('fecha_pago', '>=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->where('fecha_pago', '<=', $fechaFin);
        }
        
        return $query;
    }

    /**
     * Método estático para generar el formato de quincena
     */
    public static function generarFormatoQuincena($fecha)
    {
        $fecha = Carbon::parse($fecha);
        $quincena = $fecha->day <= 15 ? 1 : 2;
        
        return $fecha->format('Y-m') . '-' . $quincena;
    }

    /**
     * Método estático para obtener la quincena actual
     */
    public static function obtenerQuincenaActual()
    {
        return self::generarFormatoQuincena(now());
    }

    /**
     * Método estático para obtener la quincena anterior
     */
    public static function obtenerQuincenaAnterior()
    {
        $hoy = Carbon::now();
        $quincenaActual = $hoy->day <= 15 ? 1 : 2;
        
        if ($quincenaActual == 1) {
            // Si estamos en la primera quincena, la anterior es la segunda del mes pasado
            return Carbon::now()->subMonth()->format('Y-m') . '-2';
        } else {
            // Si estamos en la segunda quincena, la anterior es la primera del mismo mes
            return Carbon::now()->format('Y-m') . '-1';
        }
    }

    /**
     * Método para obtener el nombre legible de la quincena
     */
    public function getNombreQuincenaAttribute()
    {
        $partes = explode('-', $this->quincena);
        
        if (count($partes) != 3) {
            return $this->quincena;
        }
        
        $anio = $partes[0];
        $mes = $partes[1];
        $quincena = $partes[2];
        
        $nombresMeses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];
        
        $nombreMes = $nombresMeses[$mes] ?? $mes;
        $numeroQuincena = $quincena == '1' ? 'Primera' : 'Segunda';
        
        return "{$numeroQuincena} quincena de {$nombreMes} {$anio}";
    }
    
    /**
     * Marcar el pago como procesado
     * 
     * @param int $procesadoPorId ID del usuario que procesa el pago
     * @param string|null $observaciones Observaciones opcionales
     * @return bool
     */
    public function marcarComoProcesado($procesadoPorId, $observaciones = null)
    {
        $this->procesado_por = $procesadoPorId;
        $this->fecha_procesado = now();
        $this->estado = 'procesado';
        
        if ($observaciones) {
            $this->observaciones = $observaciones;
        }
        
        return $this->save();
    }
    
    /**
     * Confirmar la recepción del pago por parte del consultor
     * 
     * @param string|null $comentario Comentario opcional del consultor
     * @return bool
     */
    public function confirmarRecepcion($comentario = null)
    {
        $this->fecha_confirmacion = now();
        $this->comentario_confirmacion = $comentario;
        $this->estado = 'confirmado';
        
        return $this->save();
    }
    
    /**
     * Rechazar el pago por parte del consultor
     * 
     * @param string $comentario Motivo del rechazo
     * @return bool
     */
    public function rechazarPago($comentario)
    {
        $this->fecha_confirmacion = now();
        $this->comentario_confirmacion = $comentario;
        $this->estado = 'rechazado';
        
        return $this->save();
    }
    
    /**
     * Verificar si el pago está pendiente de procesamiento
     * 
     * @return bool
     */
    public function isPendiente()
    {
        return $this->estado === 'pendiente';
    }
    
    /**
     * Verificar si el pago ha sido procesado
     * 
     * @return bool
     */
    public function isProcesado()
    {
        return $this->estado === 'procesado';
    }
    
    /**
     * Verificar si el pago ha sido confirmado por el consultor
     * 
     * @return bool
     */
    public function isConfirmado()
    {
        return $this->estado === 'confirmado';
    }
    
    /**
     * Verificar si el pago ha sido rechazado por el consultor
     * 
     * @return bool
     */
    public function isRechazado()
    {
        return $this->estado === 'rechazado';
    }
    
    /**
     * Generar nombre de archivo para el comprobante de pago
     * 
     * @return string
     */
    public function generarNombreComprobante()
    {
        $consultor = $this->consultor->primer_nombre . '_' . $this->consultor->primer_apellido;
        $fecha = now()->format('Ymd_His');
        
        return "comprobante_pago_{$this->id}_{$consultor}_{$fecha}.pdf";
    }
    
    /**
     * Accessor para obtener el monto total en bolívares
     * 
     * @return float
     */
    public function getMontoTotalBsAttribute()
    {
        $montoUsd = $this->monto_total ?? $this->monto_base_divisas ?? 0;
        $tasaCambio = $this->tasa_cambio ?? 0;
        
        return $montoUsd * $tasaCambio;
    }
    
    /**
     * Accessor para obtener el monto neto en bolívares
     * 
     * @return float
     */
    public function getMontoNetoBsAttribute()
    {
        $montoUsd = $this->monto_neto ?? $this->total_menos_islr_divisas ?? 0;
        $tasaCambio = $this->tasa_cambio ?? 0;
        
        return $montoUsd * $tasaCambio;
    }
}