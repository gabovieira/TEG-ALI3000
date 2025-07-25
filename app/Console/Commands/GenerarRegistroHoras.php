<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\RegistroHoras;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenerarRegistroHoras extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horas:generar 
                            {--consultores=* : IDs de los consultores (opcional, por defecto todos)}
                            {--empresas=* : IDs de las empresas (opcional, por defecto todas)}
                            {--inicio= : Fecha de inicio (formato: Y-m-d, por defecto inicio de quincena actual)}
                            {--fin= : Fecha de fin (formato: Y-m-d, por defecto fin de quincena actual)}
                            {--estado=* : Estados a generar (pendiente, aprobado, rechazado)}
                            {--forzar : Forzar la creación incluso si ya existen registros}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera registros de horas para consultores en un período específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando generación de registros de horas...');
        
        // Obtener parámetros
        $consultoresIds = $this->option('consultores');
        $empresasIds = $this->option('empresas');
        $fechaInicio = $this->option('inicio');
        $fechaFin = $this->option('fin');
        $estados = $this->option('estado');
        $forzar = $this->option('forzar');
        
        // Definir el período actual si no se especifica
        $hoy = Carbon::now();
        $diaActual = $hoy->day;
        
        // Determinar si estamos en la primera o segunda quincena
        $esPrimeraQuincena = $diaActual <= 15;
        
        // Definir el rango de fechas para la quincena actual si no se especifica
        if (!$fechaInicio) {
            $fechaInicio = $esPrimeraQuincena 
                ? Carbon::create($hoy->year, $hoy->month, 1)->format('Y-m-d')
                : Carbon::create($hoy->year, $hoy->month, 16)->format('Y-m-d');
        }
        
        if (!$fechaFin) {
            $fechaFin = $esPrimeraQuincena 
                ? Carbon::create($hoy->year, $hoy->month, 15)->format('Y-m-d')
                : Carbon::create($hoy->year, $hoy->month)->endOfMonth()->format('Y-m-d');
        }
        
        // Convertir a objetos Carbon
        $fechaInicio = Carbon::parse($fechaInicio);
        $fechaFin = Carbon::parse($fechaFin);
        
        // Validar fechas
        if ($fechaInicio->gt($fechaFin)) {
            $this->error('La fecha de inicio no puede ser posterior a la fecha de fin.');
            return 1;
        }
        
        $this->info('Período: ' . $fechaInicio->format('d/m/Y') . ' - ' . $fechaFin->format('d/m/Y'));
        
        // Obtener consultores
        $consultoresQuery = Usuario::where('tipo_usuario', 'consultor')
                                ->where('estado', 'activo');
        
        if (!empty($consultoresIds)) {
            $consultoresQuery->whereIn('id', $consultoresIds);
        }
        
        $consultores = $consultoresQuery->get();
        
        if ($consultores->isEmpty()) {
            $this->error('No se encontraron consultores activos con los criterios especificados.');
            return 1;
        }
        
        $this->info('Consultores seleccionados: ' . $consultores->count());
        
        // Obtener empresas
        $empresasQuery = Empresa::where('estado', 'activa');
        
        if (!empty($empresasIds)) {
            $empresasQuery->whereIn('id', $empresasIds);
        }
        
        $empresas = $empresasQuery->get();
        
        if ($empresas->isEmpty()) {
            $this->error('No se encontraron empresas activas con los criterios especificados.');
            return 1;
        }
        
        $this->info('Empresas seleccionadas: ' . $empresas->count());
        
        // Definir estados a generar
        $estadosDisponibles = ['pendiente', 'aprobado', 'rechazado'];
        
        if (!empty($estados)) {
            $estadosValidos = array_intersect($estados, $estadosDisponibles);
            if (empty($estadosValidos)) {
                $this->error('No se especificaron estados válidos. Use: pendiente, aprobado, rechazado');
                return 1;
            }
            $estadosDisponibles = $estadosValidos;
        }
        
        $this->info('Estados a generar: ' . implode(', ', $estadosDisponibles));
        
        // Iniciar transacción
        DB::beginTransaction();
        
        try {
            $registrosCreados = 0;
            $registrosExistentes = 0;
            
            // Barra de progreso
            $progressBar = $this->output->createProgressBar($consultores->count());
            $progressBar->start();
            
            // Para cada consultor
            foreach ($consultores as $consultor) {
                // Obtener las empresas asignadas al consultor
                $empresasConsultor = $consultor->empresas;
                
                if ($empresasConsultor->isEmpty()) {
                    $this->line("\nEl consultor {$consultor->primer_nombre} {$consultor->primer_apellido} no tiene empresas asignadas.");
                    $progressBar->advance();
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
                        
                        if (!$registroExistente || $forzar) {
                            if ($registroExistente && $forzar) {
                                $registroExistente->delete();
                            }
                            
                            // Elegir un estado aleatorio
                            $estado = $estadosDisponibles[array_rand($estadosDisponibles)];
                            
                            // Datos adicionales para estados específicos
                            $aprobadoPor = null;
                            $fechaAprobacion = null;
                            $motivoRechazo = null;
                            
                            if ($estado === 'aprobado') {
                                // Buscar un administrador para aprobar
                                $admin = Usuario::where('tipo_usuario', 'administrador')->first();
                                $aprobadoPor = $admin ? $admin->id : null;
                                $fechaAprobacion = now();
                            } elseif ($estado === 'rechazado') {
                                // Buscar un administrador para rechazar
                                $admin = Usuario::where('tipo_usuario', 'administrador')->first();
                                $aprobadoPor = $admin ? $admin->id : null;
                                $fechaAprobacion = now();
                                $motivoRechazo = $this->generarMotivoRechazoAleatorio();
                            }
                            
                            // Crear el registro de horas
                            RegistroHoras::create([
                                'usuario_id' => $consultor->id,
                                'empresa_id' => $empresa->id,
                                'fecha' => $fechaActual->format('Y-m-d'),
                                'horas_trabajadas' => $horas,
                                'descripcion_actividades' => $this->generarDescripcionAleatoria(),
                                'estado' => $estado,
                                'tipo_registro' => 'manual',
                                'aprobado_por' => $aprobadoPor,
                                'fecha_aprobacion' => $fechaAprobacion,
                                'motivo_rechazo' => $motivoRechazo,
                                'fecha_creacion' => now(),
                            ]);
                            
                            $registrosCreados++;
                        } else {
                            $registrosExistentes++;
                        }
                    }
                    
                    $fechaActual->addDay();
                }
                
                $progressBar->advance();
            }
            
            $progressBar->finish();
            
            // Confirmar transacción
            DB::commit();
            
            $this->line("\n");
            $this->info("Se han creado {$registrosCreados} registros de horas para {$consultores->count()} consultores.");
            
            if ($registrosExistentes > 0) {
                $this->info("Se omitieron {$registrosExistentes} registros que ya existían. Use --forzar para sobrescribirlos.");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            
            $this->error('Error al generar registros de horas: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            
            return 1;
        }
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
     * Generar un motivo de rechazo aleatorio.
     *
     * @return string
     */
    private function generarMotivoRechazoAleatorio()
    {
        $motivos = [
            'Descripción de actividades insuficiente',
            'Horas reportadas exceden el máximo permitido',
            'Actividad no corresponde al proyecto asignado',
            'Falta de detalle en las actividades realizadas',
            'Registro duplicado',
            'Fecha incorrecta',
            'Empresa incorrecta',
        ];
        
        return $motivos[array_rand($motivos)];
    }
}