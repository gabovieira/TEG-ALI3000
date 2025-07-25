<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Verificando si la tabla usuarios existe...\n";
if (Schema::hasTable('usuarios')) {
    echo "La tabla usuarios existe.\n";
    
    echo "Contando usuarios...\n";
    $count = DB::table('usuarios')->count();
    echo "Hay {$count} usuarios en la base de datos.\n";
    
    if ($count > 0) {
        echo "Listando usuarios:\n";
        $usuarios = DB::table('usuarios')->get();
        foreach ($usuarios as $usuario) {
            echo "ID: {$usuario->id}, Nombre: {$usuario->primer_nombre} {$usuario->primer_apellido}, Email: {$usuario->email}, Tipo: {$usuario->tipo_usuario}\n";
        }
    }
} else {
    echo "La tabla usuarios no existe.\n";
}