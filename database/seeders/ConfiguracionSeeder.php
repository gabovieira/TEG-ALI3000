<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configuraciones = [
            // Configuraciones de Impuestos
            [
                'clave' => 'iva_porcentaje',
                'valor' => '16.00',
                'descripcion' => 'Porcentaje de IVA aplicado a facturas',
                'tipo' => 'numero',
                'categoria' => 'impuestos'
            ],
            [
                'clave' => 'islr_porcentaje',
                'valor' => '3.00',
                'descripcion' => 'Porcentaje de ISLR retenido en pagos',
                'tipo' => 'numero',
                'categoria' => 'impuestos'
            ],
            
            // Configuraciones de Horas
            [
                'clave' => 'horas_maximas_normales',
                'valor' => '8.0',
                'descripcion' => 'Máximo de horas normales por día',
                'tipo' => 'numero',
                'categoria' => 'horas'
            ],
            [
                'clave' => 'horas_maximas_extras',
                'valor' => '12.0',
                'descripcion' => 'Máximo de horas extras por día',
                'tipo' => 'numero',
                'categoria' => 'horas'
            ],
            
            // Configuraciones de Validación
            [
                'clave' => 'dias_limite_validacion',
                'valor' => '8',
                'descripcion' => 'Días límite para validar horas',
                'tipo' => 'numero',
                'categoria' => 'validacion'
            ],
            
            // Configuraciones de Notificaciones
            [
                'clave' => 'whatsapp_api_token',
                'valor' => '',
                'descripcion' => 'Token de API de WhatsApp',
                'tipo' => 'texto',
                'categoria' => 'notificaciones'
            ],
            
            // Configuraciones de Empresa
            [
                'clave' => 'empresa_nombre',
                'valor' => 'ALI 3000, C.A',
                'descripcion' => 'Nombre de la empresa',
                'tipo' => 'texto',
                'categoria' => 'empresa'
            ],
            [
                'clave' => 'empresa_rif',
                'valor' => 'J-123456789',
                'descripcion' => 'RIF de la empresa',
                'tipo' => 'texto',
                'categoria' => 'empresa'
            ],
            
            // Configuraciones de API
            [
                'clave' => 'bcv_api_url',
                'valor' => 'https://api.bcv.org.ve/',
                'descripcion' => 'URL de API del BCV para tasa de cambio',
                'tipo' => 'texto',
                'categoria' => 'api'
            ],
            
            // Configuraciones de Pagos
            [
                'clave' => 'prefijo_codigo_pago',
                'valor' => 'ALI',
                'descripcion' => 'Prefijo para códigos de pago',
                'tipo' => 'texto',
                'categoria' => 'pagos'
            ]
        ];

        foreach ($configuraciones as $config) {
            DB::table('configuraciones')->updateOrInsert(
                ['clave' => $config['clave']],
                array_merge($config, [
                    'fecha_actualizacion' => now()
                ])
            );
        }
    }
}