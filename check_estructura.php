<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Estructura de empresa_consultores:\n";
$columns = DB::select('DESCRIBE empresa_consultores');
foreach($columns as $column) {
    echo $column->Field . ' - ' . $column->Type . ' - ' . $column->Null . ' - ' . $column->Default . "\n";
}

echo "\nEstructura de usuarios:\n";
$columns = DB::select('DESCRIBE usuarios');
foreach($columns as $column) {
    echo $column->Field . ' - ' . $column->Type . ' - ' . $column->Null . ' - ' . $column->Default . "\n";
}

echo "\nEstructura de empresas:\n";
$columns = DB::select('DESCRIBE empresas');
foreach($columns as $column) {
    echo $column->Field . ' - ' . $column->Type . ' - ' . $column->Null . ' - ' . $column->Default . "\n";
}

echo "\nEstructura de registros_horas:\n";
$columns = DB::select('DESCRIBE registros_horas');
foreach($columns as $column) {
    echo $column->Field . ' - ' . $column->Type . ' - ' . $column->Null . ' - ' . $column->Default . "\n";
}

echo "\nEstructura de datos_laborales:\n";
$columns = DB::select('DESCRIBE datos_laborales');
foreach($columns as $column) {
    echo $column->Field . ' - ' . $column->Type . ' - ' . $column->Null . ' - ' . $column->Default . "\n";
}