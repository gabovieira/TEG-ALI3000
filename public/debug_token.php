<?php

// Este archivo es para depuración y debe ser eliminado en producción
// Permite verificar si un token es válido directamente desde el navegador

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TokenRegistro;

// Obtener token de la URL
$token = $_GET['token'] ?? null;

if (!$token) {
    echo "No se proporcionó un token. Uso: debug_token.php?token=tu_token";
    exit;
}

echo "<h1>Verificando token: {$token}</h1>";

// Verificar si el token existe en la base de datos
$tokenRegistro = TokenRegistro::where('token', $token)->first();

if ($tokenRegistro) {
    echo "<p style='color:green'>Token encontrado en la base de datos.</p>";
    echo "<p>ID: {$tokenRegistro->id}</p>";
    echo "<p>Usuario ID: {$tokenRegistro->usuario_id}</p>";
    echo "<p>Fecha de creación: {$tokenRegistro->fecha_creacion}</p>";
    echo "<p>Fecha de expiración: {$tokenRegistro->fecha_expiracion}</p>";
    echo "<p>Usado: " . ($tokenRegistro->usado ? 'Sí' : 'No') . "</p>";
    
    // Verificar si el token es válido
    $validacion = TokenRegistro::validarToken($token);
    echo "<p>Token válido: " . ($validacion['valido'] ? 'Sí' : 'No') . "</p>";
    
    if (!$validacion['valido']) {
        echo "<p style='color:red'>Mensaje de error: {$validacion['mensaje']}</p>";
    }
    
    // Verificar si el usuario asociado existe
    $usuario = $tokenRegistro->usuario;
    if ($usuario) {
        echo "<h2>Información del usuario asociado:</h2>";
        echo "<p>ID: {$usuario->id}</p>";
        echo "<p>Nombre: {$usuario->primer_nombre} {$usuario->primer_apellido}</p>";
        echo "<p>Email: {$usuario->email}</p>";
        echo "<p>Estado: {$usuario->estado}</p>";
        
        // Verificar si el usuario tiene password_hash
        echo "<p>Password hash: " . ($usuario->password_hash ? 'Establecido' : 'No establecido') . "</p>";
        
        // Verificar si el usuario tiene datos laborales
        $datosLaborales = $usuario->datosLaborales;
        if ($datosLaborales) {
            echo "<h3>Datos laborales:</h3>";
            echo "<p>Teléfono personal: " . ($datosLaborales->telefono_personal ?? 'No establecido') . "</p>";
            echo "<p>Tarifa por hora: " . ($datosLaborales->tarifa_por_hora ?? 'No establecida') . "</p>";
            echo "<p>Nivel de desarrollo: " . ($datosLaborales->nivel_desarrollo ?? 'No establecido') . "</p>";
        } else {
            echo "<p>El usuario no tiene datos laborales asociados.</p>";
        }
        
        echo "<h3>Enlaces:</h3>";
        echo "<p><a href='/registro/{$token}' target='_blank'>Ir al formulario de registro</a></p>";
    } else {
        echo "<p style='color:red'>No se encontró un usuario asociado a este token.</p>";
    }
} else {
    echo "<p style='color:red'>Token no encontrado en la base de datos.</p>";
}