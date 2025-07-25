<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TasaBcvService;

class ActualizarTasaBcv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bcv:actualizar {--force : Forzar actualización aunque ya exista tasa del día} {--manual : Ejecución manual, verificar si necesita actualización}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar la tasa BCV desde la API oficial';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏦 Iniciando actualización de tasa BCV...');
        
        $tasaBcvService = new TasaBcvService();
        
        // Si es ejecución manual, verificar si necesita actualización
        if ($this->option('manual') && !$this->option('force') && !$tasaBcvService->necesitaActualizacion()) {
            $this->info('✅ La tasa BCV ya está actualizada para hoy.');
            return 0;
        }
        
        // Si es ejecución automática (programada), siempre actualizar si han pasado 4+ horas
        if (!$this->option('manual') && !$this->option('force') && !$tasaBcvService->ultimaActualizacionAntigua(4)) {
            $this->info('⏰ Actualización automática: La tasa fue actualizada recientemente.');
            return 0;
        }
        
        try {
            $this->info('🌐 Obteniendo tasa desde API...');
            
            $tasa = $tasaBcvService->actualizarTasa();
            
            $this->info("✅ Tasa BCV actualizada exitosamente:");
            $this->line("   💰 Tasa: {$tasa->tasa} Bs/USD");
            $this->line("   📅 Fecha: {$tasa->fecha_registro->format('d/m/Y')}");
            
            // Mostrar estadísticas
            $stats = $tasaBcvService->obtenerEstadisticas();
            if ($stats) {
                $this->newLine();
                $this->info('📊 Estadísticas (últimos 30 días):');
                $this->line("   📈 Variación: {$stats['variacion_porcentaje']}%");
                $this->line("   📊 Promedio: {$stats['promedio']} Bs/USD");
                $this->line("   ⬆️  Máximo: {$stats['maximo']} Bs/USD");
                $this->line("   ⬇️  Mínimo: {$stats['minimo']} Bs/USD");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error actualizando tasa BCV: ' . $e->getMessage());
            return 1;
        }
    }
}
