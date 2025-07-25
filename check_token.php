<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TokenRegistro;
use Illuminate\Support\Facades\DB;

// Token a verificar
$token = 'R7eu5DwRZ1t040VulRqAhQ4lbrFLZBbUWSLCjd0FPBC5KMkx9txONtlGqtIeKL9X';

echo "Verificando token: {$token}\n";

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
    } else {
        echo "\nNo se encontró un usuario asociado a este token.\n";
    }
} else {
    echo "Token no encontrado en la base de datos.\n";
}