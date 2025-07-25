<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TasaBcvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear tasas BCV para los últimos 30 días
        $fechaInicio = \Carbon\Carbon::now()->subDays(30);
        $fechaFin = \Carbon\Carbon::now();
        
        $tasaBase = 36.50; // Tasa base aproximada
        
        for ($fecha = $fechaInicio->copy(); $fecha <= $fechaFin; $fecha->addDay()) {
            // Simular variación diaria de la tasa
            $variacion = rand(-50, 100) / 100; // Variación de -0.50 a +1.00
            $tasaDelDia = $tasaBase + $variacion;
            
            \App\Models\TasaBcv::create([
                'tasa' => round($tasaDelDia, 4),
                'fecha_registro' => $fecha->format('Y-m-d'),
                'origen' => 'BCV',
            ]);
            
            // Incrementar ligeramente la tasa base para simular tendencia
            $tasaBase += rand(0, 10) / 100;
        }
    }
}
