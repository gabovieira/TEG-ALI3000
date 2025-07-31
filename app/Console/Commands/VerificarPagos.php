<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerificarPagos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagos:verificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica el estado de los pagos en la base de datos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('=== VERIFICACIÓN DE PAGOS ===');
        
        // Verificar pagos
        $pagos = DB::table('pagos')->get();
        
        if ($pagos->isEmpty()) {
            $this->warn('No hay pagos en la base de datos.');
        } else {
            $this->info('PAGOS ENCONTRADOS:');
            $this->table(
                ['ID', 'Usuario ID', 'Estado', 'Total', 'Creado'],
                $pagos->map(function($pago) {
                    return [
                        'id' => $pago->id,
                        'usuario_id' => $pago->usuario_id ?? 'N/A',
                        'estado' => $pago->estado ?? 'N/A',
                        'total' => isset($pago->total) ? number_format($pago->total, 2, ',', '.') : '0,00',
                        'created_at' => $pago->created_at ?? 'N/A'
                    ];
                })
            );
        }
        
        // Verificar detalles de pagos
        $detalles = DB::table('pago_detalles')
            ->select('pago_id', DB::raw('COUNT(*) as total_detalles'))
            ->groupBy('pago_id')
            ->get();
            
        $this->info("\nDETALLES DE PAGOS:");
        if ($detalles->isEmpty()) {
            $this->warn('No hay detalles de pagos en la base de datos.');
        } else {
            $detallesArray = $detalles->map(function($item) {
                return [
                    'pago_id' => $item->pago_id,
                    'total_detalles' => $item->total_detalles
                ];
            })->toArray();
            
            $this->table(
                ['Pago ID', 'Total Detalles'],
                $detallesArray
            );
        }
        
        // Verificar usuarios con pagos
        $usuariosConPagos = DB::table('usuarios')
            ->join('pagos', 'usuarios.id', '=', 'pagos.usuario_id')
            ->select(
                'usuarios.id',
                'usuarios.primer_nombre',
                'usuarios.primer_apellido',
                DB::raw('COUNT(pagos.id) as total_pagos')
            )
            ->groupBy('usuarios.id', 'usuarios.primer_nombre', 'usuarios.primer_apellido')
            ->orderBy('usuarios.primer_nombre')
            ->get();
            
        $this->info("\nUSUARIOS CON PAGOS:");
        if ($usuariosConPagos->isEmpty()) {
            $this->warn('No hay usuarios con pagos en la base de datos.');
        } else {
            $this->table(
                ['ID', 'Nombre', 'Apellido', 'Total Pagos'],
                $usuariosConPagos->map(function($usuario) {
                    return [
                        'id' => $usuario->id,
                        'nombre' => $usuario->primer_nombre,
                        'apellido' => $usuario->primer_apellido,
                        'total_pagos' => $usuario->total_pagos
                    ];
                })
            );
        }
        
        return 0;
    }
}
