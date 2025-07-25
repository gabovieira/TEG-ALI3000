<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TokenRegistro;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Verificar si la tabla tokens_registro existe
echo "Verificando si la tabla tokens_registro existe...\n";
if (Schema::hasTable('tokens_registro')) {
    echo "La tabla tokens_registro existe.\n";
} else {
    echo "La tabla tokens_registro NO existe.\n";
}

// Verificar si la tabla usuarios existe
echo "Verificando si la tabla usuarios existe...\n";
if (Schema::hasTable('usuarios')) {
    echo "La tabla usuarios existe.\n";
} else {
    echo "La tabla usuarios NO existe.\n";
}

// Token a verificar
$token = 'R7eu5DwRZ1t040VulRqAhQ4lbrFLZBbUWSLCjd0FPBC5KMkx9txONtlGqtIeKL9X';

echo "\nVerificando token: {$token}\n";

// Verificar si el token existe en la base de datos
$tokenRegistro = TokenRegistro::where('token', $token)->first();

if ($tokenRegistro) {
    echo "Token encontrado en la base de datos.\n";
    echo "ID: {$tokenRegistro->id}\n";
    echo "Usuario ID: {$tokenRegistro->usuario_id}\n";
    echo "Fecha de creación: {$tokenRegistro->fecha_creacion}\n";
    echo "Fecha de expiración: {$tokenRegistro->fecha_expiracion}\n";
    echo "Usado: " . ($tokenRegistro->usado ? 'Sí' : 'No') . "\n";
    
    // Verificar si el token es válido
    $validacion = TokenRegistro::validarToken($token);
    echo "Token válido: " . ($validacion['valido'] ? 'Sí' : 'No') . "\n";
    
    if (!$validacion['valido']) {
        echo "Mensaje de error: {$validacion['mensaje']}\n";
    }
    
    // Verificar si el usuario asociado existe
    $usuario = $tokenRegistro->usuario;
    if ($usuario) {
        echo "\nInformación del usuario asociado:\n";
        echo "ID: {$usuario->id}\n";
        echo "Nombre: {$usuario->primer_nombre} {$usuario->primer_apellido}\n";
        echo "Email: {$usuario->email}\n";
        echo "Estado: {$usuario->estado}\n";
        
        // Verificar si el usuario tiene password_hash
        echo "Password hash: " . ($usuario->password_hash ? 'Establecido' : 'No establecido') . "\n";
        
        // Verificar si el usuario tiene datos laborales
        $datosLaborales = $usuario->datosLaborales;
        if ($datosLaborales) {
            echo "\nDatos laborales:\n";
            echo "Teléfono personal: " . ($datosLaborales->telefono_personal ?? 'No establecido') . "\n";
            echo "Tarifa por hora: " . ($datosLaborales->tarifa_por_hora ?? 'No establecida') . "\n";
            echo "Nivel de desarrollo: " . ($datosLaborales->nivel_desarrollo ?? 'No establecido') . "\n";
        } else {
            echo "\nEl usuario no tiene datos laborales asociados.\n";
        }
    } else {
        echo "\nNo se encontró un usuario asociado a este token.\n";
    }
} else {
    echo "Token no encontrado en la base de datos.\n";
}

// Verificar las rutas de registro
echo "\nVerificando rutas de registro:\n";
$routes = Route::getRoutes();
foreach ($routes as $route) {
    if (strpos($route->uri, 'registro') !== false) {
        echo "Ruta: " . $route->uri . " | Método: " . implode('|', $route->methods) . " | Nombre: " . $route->getName() . "\n";
    }
}