<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\RegistroHoras;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RegistroHorasSeeder extends Seeder
{
    /**
     * Ejecutar el seeder para crear registros de horas para los consultores.
     *
     * @return void
     */
    public function run()
    {
        // Obtener todos los consultores activos
        $consultores = Usuario::where('tipo_usuario', 'consultor')
                            ->where('estado', 'activo')
                            ->get();
        
        if ($consultores->isEmpty()) {
            $this->command->info('No hay consultores activos para generar registros de horas.');
            return;
        }
        
        // Obtener el usuario con ID 2 para generar horas no aprobadas
        $usuarioNoAprobado = Usuario::find(2);
        
        if (!$usuarioNoAprobado) {
            $this->command->info('No se encontró el usuario con ID 2 para generar horas no aprobadas.');
            return;
        }
        
        // Obtener las empresas asignadas al usuario con ID 2
        $empresasUsuario2 = $usuarioNoAprobado->empresas;
        
        if ($empresasUsuario2->isEmpty()) {
            $this->command->info('El usuario con ID 2 no tiene empresas asignadas.');
            return;
        }
        
        // Obtener todas las empresas activas
        $empresas = Empresa::where('estado', 'activa')->get();
        
        if ($empresas->isEmpty()) {
            $this->command->info('No hay empresas activas para generar registros de horas.');
            return;
        }
        
        // Definir el período actual (primera o segunda quincena del mes actual)
        $hoy = Carbon::now();
        $diaActual = $hoy->day;
        
        // Determinar si estamos en la primera o segunda quincena
        $esPrimeraQuincena = $diaActual <= 15;
        
        // Definir el rango de fechas para la quincena actual
        if ($esPrimeraQuincena) {
            $fechaInicio = Carbon::create($hoy->year, $hoy->month, 1);
            $fechaFin = Carbon::create($hoy->year, $hoy->month, 15);
        } else {
            $fechaInicio = Carbon::create($hoy->year, $hoy->month, 16);
            $fechaFin = Carbon::create($hoy->year, $hoy->month)->endOfMonth();
        }
        
        $this->command->info('Generando registros de horas para el período: ' . 
                            $fechaInicio->format('d/m/Y') . ' - ' . $fechaFin->format('d/m/Y'));
        
        // Contador para registros creados
        $registrosCreados = 0;
        
        // Generar horas NO aprobadas para el usuario con ID 2
        $this->command->info("Generando horas NO aprobadas para el usuario ID 2: {$usuarioNoAprobado->primer_nombre} {$usuarioNoAprobado->primer_apellido}");
        
        // Para cada día laborable en el rango de fechas
        $fechaActual = clone $fechaInicio;
        while ($fechaActual->lte($fechaFin)) {
            // Saltar fines de semana
            if ($fechaActual->isWeekend()) {
                $fechaActual->addDay();
                continue;
            }
            
            // 90% de probabilidad de registrar horas para este día
            if (rand(1, 100) <= 90) {
                // Elegir una empresa aleatoria de las asignadas al usuario
                $empresa = $empresasUsuario2->random();
                
                // Generar horas aleatorias entre 4 y 8
                $horas = rand(4, 8);
                
                // Verificar si ya existe un registro para este usuario, empresa y fecha
                $registroExistente = RegistroHoras::where('usuario_id', $usuarioNoAprobado->id)
                                                ->where('empresa_id', $empresa->id)
                                                ->where('fecha', $fechaActual->format('Y-m-d'))
                                                ->first();
                
                if (!$registroExistente) {
                    // Crear el registro de horas NO aprobado
                    RegistroHoras::create([
                        'usuario_id' => $usuarioNoAprobado->id,
                        'empresa_id' => $empresa->id,
                        'fecha' => $fechaActual->format('Y-m-d'),
                        'horas_trabajadas' => $horas,
                        'descripcion_actividades' => $this->generarDescripcionAleatoria(),
                        'estado' => 'pendiente', // Siempre pendiente para este usuario
                        'tipo_registro' => 'manual',
                        'fecha_creacion' => now(),
                    ]);
                    
                    $registrosCreados++;
                }
            }
            
            $fechaActual->addDay();
        }
        
        // Ahora generar horas para los demás consultores (aprobadas)
        foreach ($consultores as $consultor) {
            // Saltar el usuario con ID 2 ya que ya generamos sus horas
            if ($consultor->id == 2) {
                continue;
            }
            
            // Obtener las empresas asignadas al consultor
            $empresasConsultor = $consultor->empresas;
            
            if ($empresasConsultor->isEmpty()) {
                $this->command->info("El consultor {$consultor->primer_nombre} {$consultor->primer_apellido} no tiene empresas asignadas.");
                continue;
            }
            
            // Para cada día laborable en el rango de fechas
            $fechaActual = clone $fechaInicio;
            while ($fechaActual->lte($fechaFin)) {
                // Saltar fines de semana
                if ($fechaActual->isWeekend()) {
                    $fechaActual->addDay();
                    continue;
                }
                
                // Elegir aleatoriamente si registrar horas este día (80% de probabilidad)
                if (rand(1, 100) <= 80) {
                    // Elegir una empresa aleatoria de las asignadas al consultor
                    $empresa = $empresasConsultor->random();
                    
                    // Generar horas aleatorias entre 4 y 8
                    $horas = rand(4, 8);
                    
                    // Verificar si ya existe un registro para este consultor, empresa y fecha
                    $registroExistente = RegistroHoras::where('usuario_id', $consultor->id)
                                                    ->where('empresa_id', $empresa->id)
                                                    ->where('fecha', $fechaActual->format('Y-m-d'))
                                                    ->first();
                    
                    if (!$registroExistente) {
                        // Crear el registro de horas
                        RegistroHoras::create([
                            'usuario_id' => $consultor->id,
                            'empresa_id' => $empresa->id,
                            'fecha' => $fechaActual->format('Y-m-d'),
                            'horas_trabajadas' => $horas,
                            'descripcion_actividades' => $this->generarDescripcionAleatoria(),
                            'estado' => 'aprobado', // Siempre aprobado para los demás usuarios
                            'tipo_registro' => 'manual',
                            'fecha_creacion' => now(),
                        ]);
                        
                        $registrosCreados++;
                    }
                }
                
                $fechaActual->addDay();
            }
        }
        
        $this->command->info("Se han creado {$registrosCreados} registros de horas para {$consultores->count()} consultores.");
    }
    
    /**
     * Generar una descripción aleatoria para las actividades.
     *
     * @return string
     */
    private function generarDescripcionAleatoria()
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
    
    /**
     * Generar un estado aleatorio para el registro de horas.
     *
     * @return string
     */
    private function generarEstadoAleatorio()
    {
        $estados = [
            'pendiente' => 30,
            'aprobado' => 60,
            'rechazado' => 10,
        ];
        
        $random = rand(1, 100);
        $acumulado = 0;
        
        foreach ($estados as $estado => $probabilidad) {
            $acumulado += $probabilidad;
            if ($random <= $acumulado) {
                return $estado;
            }
        }
        
        return 'pendiente';
    }
}