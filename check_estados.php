<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\RegistroHoras;
use App\Models\Usuario;
use App\Models\Empresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== Verificación y Corrección de Estados de Horas ===\n\n";

// Verificar si hay registros en la tabla
$totalRegistros = RegistroHoras::count();
echo "Total de registros en la tabla registro_horas: {$totalRegistros}\n\n";

// Verificar registros por estado
$pendientes = RegistroHoras::where('estado', 'pendiente')->count();
$aprobados = RegistroHoras::where('estado', 'aprobado')->count();
$rechazados = RegistroHoras::where('estado', 'rechazado')->count();

echo "Registros por estado:\n";
echo "- Pendientes: {$pendientes}\n";
echo "- Aprobados: {$aprobados}\n";
echo "- Rechazados: {$rechazados}\n\n";

// Verificar si hay registros con estados inválidos
$estadosValidos = ['pendiente', 'aprobado', 'rechazado'];
$registrosInvalidos = RegistroHoras::whereNotIn('estado', $estadosValidos)->get();

if ($registrosInvalidos->count() > 0) {
    echo "Se encontraron {$registrosInvalidos->count()} registros con estados inválidos.\n";
    echo "Corrigiendo estados inválidos...\n";
    
    foreach ($registrosInvalidos as $registro) {
        echo "- Registro ID {$registro->id}: Estado '{$registro->estado}' -> 'pendiente'\n";
        $registro->estado = 'pendiente';
        $registro->save();
    }
    
    echo "Estados corregidos.\n\n";
} else {
    echo "No se encontraron registros con estados inválidos.\n\n";
}

// Verificar si hay registros sin usuario o empresa asociada
$registrosSinUsuario = RegistroHoras::whereNotExists(function ($query) {
    $query->select(DB::raw(1))
          ->from('usuarios')
          ->whereRaw('usuarios.id = registro_horas.usuario_id');
})->get();

if ($registrosSinUsuario->count() > 0) {
    echo "Se encontraron {$registrosSinUsuario->count()} registros sin usuario asociado.\n";
    echo "Estos registros pueden causar problemas. Considere eliminarlos o asignarles un usuario válido.\n\n";
} else {
    echo "No se encontraron registros sin usuario asociado.\n\n";
}

$registrosSinEmpresa = RegistroHoras::whereNotExists(function ($query) {
    $query->select(DB::raw(1))
          ->from('empresas')
          ->whereRaw('empresas.id = registro_horas.empresa_id');
})->get();

if ($registrosSinEmpresa->count() > 0) {
    echo "Se encontraron {$registrosSinEmpresa->count()} registros sin empresa asociada.\n";
    echo "Estos registros pueden causar problemas. Considere eliminarlos o asignarles una empresa válida.\n\n";
} else {
    echo "No se encontraron registros sin empresa asociada.\n\n";
}

// Verificar si hay registros con fechas inválidas
$registrosFechaInvalida = RegistroHoras::whereNull('fecha')->get();

if ($registrosFechaInvalida->count() > 0) {
    echo "Se encontraron {$registrosFechaInvalida->count()} registros con fechas inválidas.\n";
    echo "Corrigiendo fechas inválidas...\n";
    
    foreach ($registrosFechaInvalida as $registro) {
        echo "- Registro ID {$registro->id}: Fecha NULL -> " . date('Y-m-d') . "\n";
        $registro->fecha = date('Y-m-d');
        $registro->save();
    }
    
    echo "Fechas corregidas.\n\n";
} else {
    echo "No se encontraron registros con fechas inválidas.\n\n";
}

// Verificar si hay registros con horas inválidas
$registrosHorasInvalidas = RegistroHoras::where('horas_trabajadas', '<=', 0)
                                      ->orWhereNull('horas_trabajadas')
                                      ->get();

if ($registrosHorasInvalidas->count() > 0) {
    echo "Se encontraron {$registrosHorasInvalidas->count()} registros con horas inválidas.\n";
    echo "Corrigiendo horas inválidas...\n";
    
    foreach ($registrosHorasInvalidas as $registro) {
        echo "- Registro ID {$registro->id}: Horas {$registro->horas_trabajadas} -> 1.0\n";
        $registro->horas_trabajadas = 1.0;
        $registro->save();
    }
    
    echo "Horas corregidas.\n\n";
} else {
    echo "No se encontraron registros con horas inválidas.\n\n";
}

// Verificar si hay registros sin descripción
$registrosSinDescripcion = RegistroHoras::whereNull('descripcion_actividades')
                                      ->orWhere('descripcion_actividades', '')
                                      ->get();

if ($registrosSinDescripcion->count() > 0) {
    echo "Se encontraron {$registrosSinDescripcion->count()} registros sin descripción.\n";
    echo "Corrigiendo registros sin descripción...\n";
    
    foreach ($registrosSinDescripcion as $registro) {
        echo "- Registro ID {$registro->id}: Sin descripción -> 'Actividades generales'\n";
        $registro->descripcion_actividades = 'Actividades generales';
        $registro->save();
    }
    
    echo "Descripciones corregidas.\n\n";
} else {
    echo "No se encontraron registros sin descripción.\n\n";
}

echo "Verificación y corrección completada.\n";