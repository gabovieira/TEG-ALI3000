<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TokenRegistroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunos usuarios pendientes de registro con tokens
        $usuarios = [
            [
                'primer_nombre' => 'María',
                'primer_apellido' => 'González',
                'email' => 'maria.gonzalez@example.com',
                'dias_expiracion' => 7,
                'usado' => false
            ],
            [
                'primer_nombre' => 'Carlos',
                'primer_apellido' => 'Rodríguez',
                'email' => 'carlos.rodriguez@example.com',
                'dias_expiracion' => 14,
                'usado' => true
            ],
            [
                'primer_nombre' => 'Ana',
                'primer_apellido' => 'Martínez',
                'email' => 'ana.martinez@example.com',
                'dias_expiracion' => 3,
                'usado' => false
            ],
        ];

        foreach ($usuarios as $userData) {
            // Crear usuario pendiente
            $usuario = \App\Models\Usuario::create([
                'tipo_usuario' => 'consultor',
                'primer_nombre' => $userData['primer_nombre'],
                'primer_apellido' => $userData['primer_apellido'],
                'email' => $userData['email'],
                'estado' => 'pendiente_registro',
                'creado_por' => 1, // Admin user
            ]);

            // Crear token
            \App\Models\TokenRegistro::create([
                'usuario_id' => $usuario->id,
                'token' => \Illuminate\Support\Str::random(64),
                'fecha_expiracion' => now()->addDays($userData['dias_expiracion']),
                'usado' => $userData['usado'],
            ]);
        }

        // Crear un token expirado
        $usuarioExpirado = \App\Models\Usuario::create([
            'tipo_usuario' => 'consultor',
            'primer_nombre' => 'Pedro',
            'primer_apellido' => 'López',
            'email' => 'pedro.lopez@example.com',
            'estado' => 'pendiente_registro',
            'creado_por' => 1,
        ]);

        \App\Models\TokenRegistro::create([
            'usuario_id' => $usuarioExpirado->id,
            'token' => \Illuminate\Support\Str::random(64),
            'fecha_expiracion' => now()->subDays(2), // Expirado hace 2 días
            'usado' => false,
        ]);
    }
}
