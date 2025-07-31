<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ActualizarEstadoPago extends Command
{
    protected $signature = 'pago:actualizar-estado {id} {estado}';
    protected $description = 'Actualiza el estado de un pago';

    public function handle()
    {
        $id = $this->argument('id');
        $estado = $this->argument('estado');
        
        // Validar que el estado sea válido
        $estadosPermitidos = ['pendiente', 'pagado', 'confirmado', 'anulado'];
        if (!in_array($estado, $estadosPermitidos)) {
            $this->error("Estado no válido. Los estados permitidos son: " . implode(', ', $estadosPermitidos));
            return 1;
        }
        
        try {
            // Actualizar el estado del pago
            $actualizado = DB::table('pagos')
                ->where('id', $id)
                ->update([
                    'estado' => $estado,
                    'updated_at' => now()
                ]);
            
            if ($actualizado) {
                $this->info("¡Pago #{$id} actualizado a estado: {$estado} con éxito!");
                
                // Si el estado es 'pagado', actualizar también la fecha de pago
                if ($estado === 'pagado') {
                    DB::table('pagos')
                        ->where('id', $id)
                        ->update(['fecha_pago' => now()]);
                    $this->info("Fecha de pago actualizada a: " . now());
                }
                
                // Actualizar los totales en pago_detalles
                if ($estado === 'pagado' || $estado === 'confirmado') {
                    $pago = DB::table('pagos')->where('id', $id)->first();
                    
                    if ($pago) {
                        DB::table('pago_detalles')
                            ->where('pago_id', $id)
                            ->update([
                                'iva_empresa_divisas' => $pago->iva_monto ?? 0,
                                'islr_empresa_divisas' => $pago->islr_monto ?? 0,
                                'total_empresa_divisas' => $pago->monto_neto ?? 0,
                                'updated_at' => now()
                            ]);
                        $this->info("Detalles del pago actualizados con los totales correctos.");
                    }
                }
                
                return 0;
            } else {
                $this->error("No se encontró el pago con ID: {$id}");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("Error al actualizar el pago: " . $e->getMessage());
            return 1;
        }
    }
}
