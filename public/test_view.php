<?php

// Este archivo es para depuración y debe ser eliminado en producción
// Permite probar la vista de registro directamente

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TokenRegistro;
use App\Models\Usuario;
use Carbon\Carbon;

// Crear un objeto de prueba para la vista
$usuario = new Usuario();
$usuario->primer_nombre = "Usuario";
$usuario->primer_apellido = "Prueba";
$usuario->email = "test@example.com";
$usuario->estado = "pendiente_registro";

$tokenRegistro = new TokenRegistro();
$tokenRegistro->token = "token_prueba";
$tokenRegistro->fecha_expiracion = Carbon::now()->addDays(7);
$tokenRegistro->usado = false;

// Renderizar la vista
echo view('auth.registro', [
    'token' => 'token_prueba',
    'usuario' => $usuario,
    'tokenRegistro' => $tokenRegistro
]);