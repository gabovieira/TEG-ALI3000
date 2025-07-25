<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TokenRegistro;
use App\Models\Usuario;
use App\Models\DatosLaborales;
use Illuminate\Support\Facades\Hash;

// Crear un nuevo usuario para pruebas
$email = "test" . time() . "@example.com";
$usuario = new Usuario();
$usuario->primer_nombre = "Test";
$usuario->primer_apellido = "User";
$usuario->email = $email;
$usuario->tipo_usuario = "consultor";
$usuario->estado = "pendiente_registro";
$usuario->fecha_creacion = now();
$usuario->save();

echo "Usuario creado con ID: {$usuario->id}\n";

// Crear datos laborales para el usuario
$datosLaborales = new DatosLaborales();
$datosLaborales->usuario_id = $usuario->id;
$datosLaborales->tarifa_por_hora = 10.00;
$datosLaborales->nivel_desarrollo = "junior";
$datosLaborales->save();

echo "Datos laborales creados para el usuario\n";

// Generar token de registro
$token = TokenRegistro::generarToken($usuario->id, 7);

echo "Token generado: {$token->token}\n";
echo "URL de registro: " . route('registro.formulario', ['token' => $token->token]) . "\n";