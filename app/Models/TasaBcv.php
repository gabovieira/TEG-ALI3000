<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TasaBcv extends Model
{
    use HasFactory;

    protected $table = 'tasas_bcv';
    
    public $timestamps = false;

    protected $fillable = [
        'tasa',
        'fecha_registro',
        'origen'
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'tasa' => 'decimal:4',
        'fecha_creacion' => 'datetime'
    ];

    /**
     * Obtener la tasa BCV actual (del día actual)
     */
    public static function actual()
    {
        return self::where('fecha_registro', Carbon::today())
                   ->orderBy('fecha_creacion', 'desc')
                   ->first();
    }

    /**
     * Obtener la tasa BCV más reciente disponible
     */
    public static function reciente()
    {
        return self::orderBy('fecha_registro', 'desc')
                   ->orderBy('id', 'desc')
                   ->first();
    }

    /**
     * Obtener tasa por fecha específica
     */
    public static function porFecha($fecha)
    {
        return self::where('fecha_registro', $fecha)
                   ->orderBy('fecha_creacion', 'desc')
                   ->first();
    }
}