<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\RegistroHoras;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "Generando registros de horas de prueba...\n";

// Verificar si hay consultores
$consultores = Usuario::where('tipo_usuario', 'consultor')->get();
if ($consultores->isEmpty()) {
    echo "No hay consultores en la base de datos. Creando uno de prueba...\n";
    
    $consultor = new Usuario();
    $consultor->tipo_usuario = 'consultor';
    $consultor->primer_nombre = 'Juan';
    $consultor->primer_apellido = 'Pérez';
    $consultor->email = 'juan.perez@example.com';
    $consultor->password_hash = bcrypt('password');
    $consultor->estado = 'activo';
    $consultor->fecha_creacion = now();
    $consultor->save();
    
    $consultores = collect([$consultor]);
    
    echo "Consultor creado con ID: {$consultor->id}\n";
}

// Verificar si hay empresas
$empresas = Empresa::where('estado', 'activa')->get();
if ($empresas->isEmpty()) {
    echo "No hay empresas en la base de datos. Creando una de prueba...\n";
    
    $empresa = new Empresa();
    $empresa->nombre = 'Empresa de Prueba';
    $empresa->rif = 'J-12345678-9';
    $empresa->direccion = 'Dirección de prueba';
    $empresa->telefono = '0412-1234567';
    $empresa->email = 'empresa@example.com';
    $empresa->estado = 'activa';
    $empresa->fecha_creacion = now();
    $empresa->save();
    
    $empresas = collect([$empresa]);
    
    echo "Empresa creada con ID: {$empresa->id}\n";
}

// Asignar consultores a empresas si no tienen asignaciones
foreach ($consultores as $consultor) {
    $asignaciones = DB::table('empresa_consultores')
        ->where('usuario_id', $consultor->id)
        ->count();
    
    if ($asignaciones == 0) {
        echo "Asignando consultor {$consultor->id} a empresas...\n";
        
        foreach ($empresas as $empresa) {
            DB::table('empresa_consultores')->insert([
                'usuario_id' => $consultor->id,
                'empresa_id' => $empresa->id,
                'fecha_asignacion' => now(),
                'tipo_asignacion' => 'principal',
                'estado' => 'activo',
                'observaciones' => 'Asignación de prueba'
            ]);
            
            echo "Consultor {$consultor->id} asignado a empresa {$empresa->id}\n";
        }
    }
}

// Generar registros de horas para los últimos 30 días
$fechaInicio = Carbon::now()->subDays(30);
$fechaFin = Carbon::now();

$registrosCreados = 0;

foreach ($consultores as $consultor) {
    foreach ($empresas as $empresa) {
        $fechaActual = clone $fechaInicio;
        
        while ($fechaActual->lte($fechaFin)) {
            // Saltar fines de semana
            if ($fechaActual->isWeekend()) {
                $fechaActual->addDay();
                continue;
            }
            
            // Generar entre 0 y 3 registros por día (aleatoriamente)
            $numRegistros = rand(0, 3);
            
            for ($i = 0; $i < $numRegistros; $i++) {
                // Generar horas aleatorias entre 1 y 8
                $horas = rand(1, 8);
                
                // Generar estado aleatorio
                $estados = ['pendiente', 'aprobado', 'rechazado'];
                $estado = $estados[array_rand($estados)];
                
                // Datos adicionales para estados específicos
                $aprobadoPor = null;
                $fechaAprobacion = null;
                $motivoRechazo = null;
                
                if ($estado === 'aprobado' || $estado === 'rechazado') {
                    // Buscar un administrador para aprobar/rechazar
                    $admin = Usuario::where('tipo_usuario', 'admin')->first();
                    $aprobadoPor = $admin ? $admin->id : null;
                    $fechaAprobacion = now();
                    
                    if ($estado === 'rechazado') {
                        $motivos = [
                            'Descripción de actividades insuficiente',
                            'Horas reportadas exceden el máximo permitido',
                            'Actividad no corresponde al proyecto asignado',
                            'Falta de detalle en las actividades realizadas',
                            'Registro duplicado'
                        ];
                        $motivoRechazo = $motivos[array_rand($motivos)];
                    }
                }
                
                // Crear el registro de horas
                $registro = new RegistroHoras();
                $registro->usuario_id = $consultor->id;
                $registro->empresa_id = $empresa->id;
                $registro->fecha = $fechaActual->format('Y-m-d');
                $registro->horas_trabajadas = $horas;
                $registro->descripcion_actividades = generarDescripcionAleatoria();
                $registro->estado = $estado;
                $registro->tipo_registro = 'manual';
                $registro->aprobado_por = $aprobadoPor;
                $registro->fecha_aprobacion = $fechaAprobacion;
                $registro->motivo_rechazo = $motivoRechazo;
                $registro->fecha_creacion = now();
                $registro->save();
                
                $registrosCreados++;
            }
            
            $fechaActual->addDay();
        }
    }
}

echo "Se han creado {$registrosCreados} registros de horas de prueba.\n";

/**
 * Generar una descripción aleatoria para las actividades.
 *
 * @return string
 */
function generarDescripcionAleatoria()
{
    $actividades = [
        'Desarrollo de funcionalidades para el módulo de usuarios',
        'Corrección de errores en el sistema de autenticación',
        'Implementación de nuevas características en el dashboard',
        'Optimización de consultas a la base de datos',
        'Reuniones de planificación con el equipo',
        'Diseño de interfaces de usuario',
        'Pruebas de integración del sistema',
        'Documentación de código y procesos',
        'Refactorización de código legacy',
        'Implementación de pruebas unitarias',
        'Desarrollo de API REST',
        'Configuración de servidores y despliegue',
        'Soporte técnico a usuarios finales',
        'Análisis de requerimientos con el cliente',
        'Investigación de nuevas tecnologías',
    ];
    
    return $actividades[array_rand($actividades)];
}