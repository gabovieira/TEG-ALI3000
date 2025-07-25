<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Obtener todas las tablas
$tables = DB::select('SHOW TABLES');
$tableColumn = 'Tables_in_' . env('DB_DATABASE');

echo "Tablas en la base de datos:\n";
foreach ($tables as $table) {
    echo "- " . $table->$tableColumn . "\n";
}

// Verificar si existen las tablas específicas
echo "\nVerificando tablas específicas:\n";
echo "registro_horas existe: " . (Schema::hasTable('registro_horas') ? 'Sí' : 'No') . "\n";
echo "registros_horas existe: " . (Schema::hasTable('registros_horas') ? 'Sí' : 'No') . "\n";

// Si existe registro_horas, mostrar su estructura
if (Schema::hasTable('registro_horas')) {
    echo "\nEstructura de la tabla registro_horas:\n";
    $columns = Schema::getColumnListing('registro_horas');
    foreach ($columns as $column) {
        echo "- " . $column . "\n";
    }
    
    // Contar registros
    $count = DB::table('registro_horas')->count();
    echo "\nNúmero de registros en registro_horas: " . $count . "\n";
}

// Si existe registros_horas, mostrar su estructura
if (Schema::hasTable('registros_horas')) {
    echo "\nEstructura de la tabla registros_horas:\n";
    $columns = Schema::getColumnListing('registros_horas');
    foreach ($columns as $column) {
        echo "- " . $column . "\n";
    }
    
    // Contar registros
    $count = DB::table('registros_horas')->count();
    echo "\nNúmero de registros en registros_horas: " . $count . "\n";
}

// Verificar qué tabla está usando el modelo RegistroHoras
echo "\nTabla que usa el modelo RegistroHoras:\n";
$model = new App\Models\RegistroHoras();
echo "Nombre de la tabla: " . $model->getTable() . "\n";