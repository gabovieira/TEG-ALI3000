<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Verificar si la tabla existe
if (Schema::hasTable('registro_horas')) {
    echo "La tabla registro_horas existe.\n";
    
    // Contar registros
    $count = DB::table('registro_horas')->count();
    echo "Total de registros: {$count}\n";
    
    if ($count > 0) {
        // Mostrar algunos registros
        echo "\nMostrando los primeros 5 registros:\n";
        $registros = DB::table('registro_horas')
            ->join('usuarios', 'registro_horas.usuario_id', '=', 'usuarios.id')
            ->join('empresas', 'registro_horas.empresa_id', '=', 'empresas.id')
            ->select('registro_horas.*', 'usuarios.primer_nombre', 'usuarios.primer_apellido', 'empresas.nombre as empresa_nombre')
            ->limit(5)
            ->get();
        
        foreach ($registros as $registro) {
            echo "ID: {$registro->id}, ";
            echo "Consultor: {$registro->primer_nombre} {$registro->primer_apellido}, ";
            echo "Empresa: {$registro->empresa_nombre}, ";
            echo "Fecha: {$registro->fecha}, ";
            echo "Horas: {$registro->horas_trabajadas}, ";
            echo "Estado: {$registro->estado}\n";
        }
    }
} else {
    echo "La tabla registro_horas NO existe.\n";
    
    // Listar todas las tablas
    echo "\nTablas disponibles en la base de datos:\n";
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- {$tableName}\n";
    }
}