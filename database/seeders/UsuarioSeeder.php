<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        Usuario::create([
            'tipo_usuario' => 'admin',
            'primer_nombre' => 'Admin',
            'primer_apellido' => 'ALI3000',
            'email' => 'admin@ali3000.com',
            'password_hash' => Hash::make('admin123'),
            'estado' => 'activo',
            'fecha_creacion' => now(),
            'fecha_actualizacion' => now(),
        ]);

        // Crear usuario consultor de prueba
        Usuario::create([
            'tipo_usuario' => 'consultor',
            'primer_nombre' => 'Juan',
            'primer_apellido' => 'Pérez',
            'email' => 'consultor@ali3000.com',
            'password_hash' => Hash::make('consultor123'),
            'estado' => 'activo',
            'fecha_creacion' => now(),
            'fecha_actualizacion' => now(),
        ]);
    }
}