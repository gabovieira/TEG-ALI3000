<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TokenRegistro;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

// Token a verificar
$token = 'R7eu5DwRZ1t040VulRqAhQ4lbrFLZBbUWSLCjd0FPBC5KMkx9txONtlGqtIeKL9X';

echo "Verificando token: {$token}\n";

// Verificar si el token existe en la base de datos
$tokenRegistro = TokenRegistro::where('token', $token)->first();

if ($tokenRegistro) {
    echo "Token encontrado en la base de datos.\n";
    
    // Verificar si el token es válido
    $validacion = TokenRegistro::validarToken($token);
    echo "Token válido: " . ($validacion['valido'] ? 'Sí' : 'No') . "\n";
    
    if ($validacion['valido']) {
        $usuario = $tokenRegistro->usuario;
        
        if ($usuario) {
            echo "Usuario encontrado: {$usuario->primer_nombre} {$usuario->primer_apellido}\n";
            
            // Simular el proceso de registro
            echo "Simulando proceso de registro...\n";
            
            try {
                // Actualizar usuario
                $usuario->password_hash = Hash::make('password123');
                $usuario->estado = 'activo';
                $usuario->save();
                
                echo "Usuario actualizado correctamente.\n";
                
                // Verificar si el usuario tiene datos laborales
                $datosLaborales = $usuario->datosLaborales;
                
                if ($datosLaborales) {
                    // Actualizar datos laborales
                    $datosLaborales->telefono_personal = '+584121234567';
                    $datosLaborales->save();
                    
                    echo "Datos laborales actualizados correctamente.\n";
                } else {
                    // Crear datos laborales
                    $datosLaborales = $usuario->datosLaborales()->create([
                        'telefono_personal' => '+584121234567',
                        'tarifa_por_hora' => 0,
                        'nivel_desarrollo' => 'junior',
                    ]);
                    
                    echo "Datos laborales creados correctamente.\n";
                }
                
                // Marcar token como usado
                $tokenRegistro->usado = true;
                $tokenRegistro->save();
                
                echo "Token marcado como usado correctamente.\n";
                
                echo "Proceso de registro simulado correctamente.\n";
            } catch (\Exception $e) {
                echo "Error al simular proceso de registro: {$e->getMessage()}\n";
                echo "Trace: {$e->getTraceAsString()}\n";
            }
        } else {
            echo "No se encontró el usuario asociado a este token.\n";
        }
    } else {
        echo "Mensaje de error: {$validacion['mensaje']}\n";
    }
} else {
    echo "Token no encontrado en la base de datos.\n";
}