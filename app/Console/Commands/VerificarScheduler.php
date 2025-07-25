<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TasaBcv;
use Carbon\Carbon;

class VerificarScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar el estado del scheduler y las tareas programadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📅 Estado del Scheduler - Sistema ALI3000');
        $this->newLine();
        
        // Verificar última actualización de tasa BCV
        $ultimaTasa = TasaBcv::orderBy('fecha_creacion', 'desc')->first();
        
        if ($ultimaTasa) {
            $horasDesdeActualizacion = Carbon::now()->diffInHours($ultimaTasa->fecha_creacion);
            $this->info("💰 Última tasa BCV:");
            $this->line("   Tasa: {$ultimaTasa->tasa} Bs/USD");
            $this->line("   Fecha: {$ultimaTasa->fecha_registro->format('d/m/Y')}");
            $this->line("   Hace: {$horasDesdeActualizacion} horas");
            
            if ($horasDesdeActualizacion > 24) {
                $this->warn("⚠️  La tasa no se ha actualizado en más de 24 horas");
            } elseif ($horasDesdeActualizacion > 8) {
                $this->comment("⏰ La tasa necesita actualización pronto");
            } else {
                $this->info("✅ Tasa actualizada recientemente");
            }
        } else {
            $this->error("❌ No hay tasas BCV registradas");
        }
        
        $this->newLine();
        $this->info("🕘 Tareas programadas:");
        $this->line("   • Actualización BCV: 09:00 y 15:00 diariamente");
        $this->newLine();
        
        $this->comment("Para activar el scheduler en producción, agregar al crontab:");
        $this->line("* * * * * cd " . base_path() . " && php artisan schedule:run >> /dev/null 2>&1");
        
        return 0;
    }
}
