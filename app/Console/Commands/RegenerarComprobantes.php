<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pago;
use App\Services\PagoService;

class RegenerarComprobantes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comprobantes:regenerar {--all : Regenerar todos los comprobantes} {--pago= : Regenerar comprobante específico por ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerar comprobantes de pago con el nuevo diseño';

    protected $pagoService;

    public function __construct(PagoService $pagoService)
    {
        parent::__construct();
        $this->pagoService = $pagoService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('pago')) {
            // Regenerar comprobante específico
            $pagoId = $this->option('pago');
            $this->regenerarComprobante($pagoId);
        } elseif ($this->option('all')) {
            // Regenerar todos los comprobantes
            $this->regenerarTodosLosComprobantes();
        } else {
            $this->error('Debes especificar --all para todos los comprobantes o --pago=ID para uno específico');
            return 1;
        }

        return 0;
    }

    private function regenerarComprobante($pagoId)
    {
        try {
            $pago = Pago::with(['consultor', 'detalles.empresa', 'procesador', 'datosBancarios'])->findOrFail($pagoId);
            
            $this->info("Regenerando comprobante para pago ID: {$pagoId}");
            
            // Eliminar archivo anterior si existe
            if ($pago->comprobante_pago) {
                $rutaAnterior = storage_path('app/public/comprobantes/' . $pago->comprobante_pago);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }
            
            // Generar nuevo comprobante
            $nombreArchivo = $this->pagoService->generarComprobante($pago);
            $pago->comprobante_pago = $nombreArchivo;
            $pago->save();
            
            $this->info("✅ Comprobante regenerado: {$nombreArchivo}");
            
        } catch (\Exception $e) {
            $this->error("❌ Error regenerando comprobante {$pagoId}: " . $e->getMessage());
        }
    }

    private function regenerarTodosLosComprobantes()
    {
        $pagos = Pago::whereNotNull('comprobante_pago')->get();
        
        if ($pagos->isEmpty()) {
            $this->info('No hay comprobantes para regenerar.');
            return;
        }

        $this->info("Encontrados {$pagos->count()} comprobantes para regenerar...");
        
        $bar = $this->output->createProgressBar($pagos->count());
        $bar->start();

        $exitosos = 0;
        $errores = 0;

        foreach ($pagos as $pago) {
            try {
                // Cargar relaciones necesarias
                $pago->load(['consultor', 'detalles.empresa', 'procesador', 'datosBancarios']);
                
                // Eliminar archivo anterior si existe
                if ($pago->comprobante_pago) {
                    $rutaAnterior = storage_path('app/public/comprobantes/' . $pago->comprobante_pago);
                    if (file_exists($rutaAnterior)) {
                        unlink($rutaAnterior);
                    }
                }
                
                // Generar nuevo comprobante
                $nombreArchivo = $this->pagoService->generarComprobante($pago);
                $pago->comprobante_pago = $nombreArchivo;
                $pago->save();
                
                $exitosos++;
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Error regenerando comprobante ID {$pago->id}: " . $e->getMessage());
                $errores++;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Proceso completado:");
        $this->info("   - Exitosos: {$exitosos}");
        if ($errores > 0) {
            $this->warn("   - Errores: {$errores}");
        }
    }
}
