<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feriado;

class FeriadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feriados = [
            // Feriados 2025
            [
                'fecha' => '2025-01-01',
                'descripcion' => 'Año Nuevo',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-02-10',
                'descripcion' => 'Lunes de Carnaval',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-02-11',
                'descripcion' => 'Martes de Carnaval',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-03-19',
                'descripcion' => 'San José',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-04-17',
                'descripcion' => 'Jueves Santo',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-04-18',
                'descripcion' => 'Viernes Santo',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-04-19',
                'descripcion' => 'Declaración de la Independencia',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-05-01',
                'descripcion' => 'Día del Trabajador',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-06-24',
                'descripcion' => 'Batalla de Carabobo',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-07-05',
                'descripcion' => 'Día de la Independencia',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-07-24',
                'descripcion' => 'Natalicio del Libertador',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-10-12',
                'descripcion' => 'Día de la Resistencia Indígena',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-12-24',
                'descripcion' => 'Nochebuena',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-12-25',
                'descripcion' => 'Navidad',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            [
                'fecha' => '2025-12-31',
                'descripcion' => 'Fin de Año',
                'tipo' => 'nacional',
                'activo' => true,
            ],
            // Feriados bancarios adicionales
            [
                'fecha' => '2025-08-15',
                'descripcion' => 'Asunción de la Virgen',
                'tipo' => 'bancario',
                'activo' => true,
            ],
            [
                'fecha' => '2025-11-01',
                'descripcion' => 'Día de Todos los Santos',
                'tipo' => 'bancario',
                'activo' => true,
            ],
        ];

        foreach ($feriados as $feriado) {
            Feriado::create($feriado);
        }
    }
}