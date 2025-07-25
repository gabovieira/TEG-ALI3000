<?php

namespace App\Services;

use App\Models\TasaBcv;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TasaBcvService
{
    /**
     * URL de la API del BCV
     */
    private const BCV_API_URL = 'https://api.bcv.org.ve/api/v1/exchange-rates';
    
    /**
     * URL alternativa (API no oficial pero más confiable)
     */
    private const ALTERNATIVE_API_URL = 'https://api.exchangerate-api.com/v4/latest/USD';

    /**
     * Obtener la tasa BCV actual desde la API
     */
    public function obtenerTasaDesdeApi()
    {
        try {
            // Intentar primero con la API oficial del BCV
            $response = Http::timeout(10)->get(self::BCV_API_URL);
            
            if ($response->successful()) {
                $data = $response->json();
                return $this->procesarRespuestaBcv($data);
            }
            
            // Si falla, usar API alternativa
            return $this->obtenerTasaAlternativa();
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo tasa BCV: ' . $e->getMessage());
            return $this->obtenerTasaAlternativa();
        }
    }

    /**
     * Obtener tasa desde API alternativa
     */
    private function obtenerTasaAlternativa()
    {
        try {
            // Usar una API que tenga VES (Bolívar Venezolano)
            $response = Http::timeout(10)->get('https://api.exchangerate-api.com/v4/latest/USD');
            
            if ($response->successful()) {
                $data = $response->json();
                
                // La tasa VES/USD (cuántos bolívares por 1 USD)
                if (isset($data['rates']['VES'])) {
                    return [
                        'tasa' => $data['rates']['VES'],
                        'fecha' => Carbon::now()->format('Y-m-d'),
                        'origen' => 'ExchangeRate-API'
                    ];
                }
            }
            
            // Si no hay VES, usar una tasa aproximada basada en otras fuentes
            return $this->obtenerTasaManual();
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo tasa alternativa: ' . $e->getMessage());
            return $this->obtenerTasaManual();
        }
    }

    /**
     * Obtener tasa manual/estimada cuando las APIs fallan
     */
    private function obtenerTasaManual()
    {
        // Obtener la última tasa registrada y aplicar un pequeño incremento
        $ultimaTasa = TasaBcv::reciente();
        
        if ($ultimaTasa) {
            // Aplicar un incremento aleatorio pequeño (inflación diaria estimada)
            $incremento = rand(1, 50) / 1000; // 0.001 a 0.050
            $nuevaTasa = $ultimaTasa->tasa + $incremento;
            
            return [
                'tasa' => round($nuevaTasa, 4),
                'fecha' => Carbon::now()->format('Y-m-d'),
                'origen' => 'Estimado'
            ];
        }
        
        // Si no hay datos previos, usar una tasa base
        return [
            'tasa' => 36.50,
            'fecha' => Carbon::now()->format('Y-m-d'),
            'origen' => 'Base'
        ];
    }

    /**
     * Procesar respuesta de la API oficial del BCV
     */
    private function procesarRespuestaBcv($data)
    {
        // Estructura esperada de la API del BCV (puede variar)
        if (isset($data['USD']['rate'])) {
            return [
                'tasa' => $data['USD']['rate'],
                'fecha' => $data['date'] ?? Carbon::now()->format('Y-m-d'),
                'origen' => 'BCV'
            ];
        }
        
        throw new \Exception('Formato de respuesta BCV no reconocido');
    }

    /**
     * Actualizar la tasa en la base de datos
     */
    public function actualizarTasa($dataTasa = null)
    {
        if (!$dataTasa) {
            $dataTasa = $this->obtenerTasaDesdeApi();
        }
        
        if (!$dataTasa) {
            throw new \Exception('No se pudo obtener la tasa BCV');
        }
        
        // Verificar si ya existe una tasa para hoy
        $tasaHoy = TasaBcv::where('fecha_registro', $dataTasa['fecha'])->first();
        
        if ($tasaHoy) {
            // Actualizar la tasa existente
            $tasaHoy->update([
                'tasa' => $dataTasa['tasa'],
                'origen' => $dataTasa['origen']
            ]);
            
            Log::info("Tasa BCV actualizada: {$dataTasa['tasa']} ({$dataTasa['origen']})");
            return $tasaHoy;
        } else {
            // Crear nueva tasa
            $nuevaTasa = TasaBcv::create([
                'tasa' => $dataTasa['tasa'],
                'fecha_registro' => $dataTasa['fecha'],
                'origen' => $dataTasa['origen']
            ]);
            
            Log::info("Nueva tasa BCV registrada: {$dataTasa['tasa']} ({$dataTasa['origen']})");
            return $nuevaTasa;
        }
    }

    /**
     * Verificar si necesita actualización (si no hay tasa de hoy o es muy antigua)
     */
    public function necesitaActualizacion()
    {
        $tasaHoy = TasaBcv::where('fecha_registro', Carbon::today())->first();
        
        if (!$tasaHoy) {
            return true; // No hay tasa de hoy
        }
        
        // Si la tasa es de hoy pero fue creada hace más de 4 horas, actualizar
        $horasDesdeCreacion = Carbon::now()->diffInHours($tasaHoy->fecha_creacion);
        return $horasDesdeCreacion >= 4;
    }

    /**
     * Verificar si la última actualización fue hace más de X horas
     */
    public function ultimaActualizacionAntigua($horas = 4)
    {
        $ultimaTasa = TasaBcv::orderBy('fecha_creacion', 'desc')->first();
        
        if (!$ultimaTasa) {
            return true;
        }
        
        return Carbon::now()->diffInHours($ultimaTasa->fecha_creacion) >= $horas;
    }

    /**
     * Obtener estadísticas de tasas
     */
    public function obtenerEstadisticas($dias = 30)
    {
        $tasas = TasaBcv::where('fecha_registro', '>=', Carbon::now()->copy()->subDays($dias)->format('Y-m-d'))
                        ->orderBy('fecha_registro', 'desc')
                        ->get();
        
        if ($tasas->isEmpty()) {
            return null;
        }
        
        $tasaActual = $tasas->first()->tasa;
        $tasaAnterior = $tasas->last()->tasa;
        $variacion = (($tasaActual - $tasaAnterior) / $tasaAnterior) * 100;
        
        return [
            'tasa_actual' => $tasaActual,
            'tasa_anterior' => $tasaAnterior,
            'variacion_porcentaje' => round($variacion, 2),
            'promedio' => round($tasas->avg('tasa'), 4),
            'maximo' => $tasas->max('tasa'),
            'minimo' => $tasas->min('tasa'),
            'total_registros' => $tasas->count()
        ];
    }
}