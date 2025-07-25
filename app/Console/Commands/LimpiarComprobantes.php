<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pago;
use Illuminate\Support\Facades\Storage;

class LimpiarComprobantes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comprobantes:limpiar {--force : Forzar limpieza sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar todos los comprobantes existentes para que se regeneren con el nuevo diseño';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pagos = Pago::whereNotNull('comprobante_pago')->get();
        
        if ($pagos->isEmpty()) {
            $this->info('No hay comprobantes para limpiar.');
            return 0;
        }

        $this->info("Se encontraron {$pagos->count()} comprobantes para limpiar.");
        
        if (!$this->option('force')) {
            if (!$this->confirm('¿Estás seguro de que quieres limpiar todos los comprobantes? Se regenerarán automáticamente cuando se descarguen.')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        $bar = $this->output->createProgressBar($pagos->count());
        $bar->start();

        $eliminados = 0;
        $errores = 0;

        foreach ($pagos as $pago) {
            try {
                // Eliminar archivo físico si existe
                if ($pago->comprobante_pago) {
                    $rutaArchivo = storage_path('app/public/comprobantes/' . $pago->comprobante_pago);
                    if (file_exists($rutaArchivo)) {
                        unlink($rutaArchivo);
                    }
                }
                
                // Limpiar referencia en la base de datos
                $pago->comprobante_pago = null;
                $pago->save();
                
                $eliminados++;
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Error limpiando comprobante ID {$pago->id}: " . $e->getMessage());
                $errores++;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Limpieza completada:");
        $this->info("   - Comprobantes eliminados: {$eliminados}");
        if ($errores > 0) {
            $this->warn("   - Errores: {$errores}");
        }
        $this->info("Los comprobantes se regenerarán automáticamente con el nuevo diseño cuando se descarguen.");

        return 0;
    }
}
