<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;

class EmpresaConsultorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunas empresas de ejemplo si no existen
        $empresas = [
            [
                'nombre' => 'Empresa A',
                'rif' => 'J-12345678-0',
                'tipo_empresa' => 'S.A.',
                'direccion' => 'Caracas, Venezuela',
                'telefono' => '+58 212 123 4567',
                'email' => 'contacto@empresaa.com',
                'estado' => 'activa'
            ],
            [
                'nombre' => 'Empresa B',
                'rif' => 'J-87654321-0',
                'tipo_empresa' => 'C.A.',
                'direccion' => 'Valencia, Venezuela',
                'telefono' => '+58 241 987 6543',
                'email' => 'contacto@empresab.com',
                'estado' => 'activa'
            ],
            [
                'nombre' => 'Empresa C',
                'rif' => 'J-11223344-0',
                'tipo_empresa' => 'Otro',
                'direccion' => 'Maracaibo, Venezuela',
                'telefono' => '+58 261 555 7777',
                'email' => 'contacto@empresac.com',
                'estado' => 'activa'
            ]
        ];

        foreach ($empresas as $empresaData) {
            Empresa::firstOrCreate(
                ['rif' => $empresaData['rif']],
                $empresaData
            );
        }

        // Obtener todas las empresas y consultores
        $empresas = Empresa::all();
        $consultores = Usuario::where('tipo_usuario', 'consultor')->get();

        // Asignar empresas a consultores
        foreach ($consultores as $consultor) {
            // Asignar al menos una empresa a cada consultor
            $empresasAsignar = $empresas->random(rand(1, $empresas->count()));
            
            foreach ($empresasAsignar as $empresa) {
                // Verificar si ya existe la asignación
                $existeAsignacion = DB::table('empresa_consultores')
                    ->where('usuario_id', $consultor->id)
                    ->where('empresa_id', $empresa->id)
                    ->exists();
                
                if (!$existeAsignacion) {
                    DB::table('empresa_consultores')->insert([
                        'usuario_id' => $consultor->id,
                        'empresa_id' => $empresa->id,
                        'fecha_asignacion' => now(),
                        'tipo_asignacion' => ['tiempo_completo', 'parcial', 'temporal'][rand(0, 2)],
                        'estado' => 'activo',
                        'observaciones' => 'Asignación automática por seeder'
                    ]);
                }
            }
        }

        $this->command->info('Empresas asignadas a consultores correctamente.');
    }
}