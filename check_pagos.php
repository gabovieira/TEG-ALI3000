<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illware\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Consulta para ver los pagos
echo "=== PAGOS EN LA BASE DE DATOS ===\n";
$pagos = DB::select('SELECT id, usuario_id, estado, total, created_at FROM pagos');

if (empty($pagos)) {
    echo "No hay pagos en la base de datos.\n";
} else {
    echo "ID\tUsuario\tEstado\t\tTotal\t\tCreado\n";
    echo str_repeat("-", 80) . "\n";
    
    foreach ($pagos as $pago) {
        echo "{$pago->id}\t";
        echo "{$pago->usuario_id}\t";
        echo str_pad($pago->estado, 10, ' '). "\t";
        echo number_format($pago->total, 2, ',', '.') . "\t";
        echo $pago->created_at . "\n";
    }
}

// Verificar relaciones
echo "\n=== VERIFICANDO RELACIONES ===\n";

try {
    // Verificar si hay datos en pago_detalles
    $detalles = DB::select('SELECT pago_id, COUNT(*) as total FROM pago_detalles GROUP BY pago_id');
    echo "Detalles de pagos encontrados: " . count($detalles) . " pagos con detalles.\n";
    
    // Verificar si hay usuarios con pagos
    $usuarios = DB::select('SELECT u.id, u.primer_nombre, u.primer_apellido, COUNT(p.id) as total_pagos 
                           FROM usuarios u 
                           LEFT JOIN pagos p ON u.id = p.usuario_id 
                           WHERE p.id IS NOT NULL 
                           GROUP BY u.id, u.primer_nombre, u.primer_apellido');
    
    echo "Usuarios con pagos: " . count($usuarios) . "\n";
    foreach ($usuarios as $usuario) {
        echo "- {$usuario->primer_nombre} {$usuario->primer_apellido} (ID: {$usuario->id}): {$usuario->total_pagos} pagos\n";
    }
    
} catch (Exception $e) {
    echo "Error al verificar relaciones: " . $e->getMessage() . "\n";
}
