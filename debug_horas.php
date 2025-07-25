<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\RegistroHoras;
use App\Models\Usuario;
use App\Models\Empresa;
use Carbon\Carbon;

echo "=== Depuración de Registros de Horas ===\n\n";

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

// Verificar registros por consultor
$consultores = Usuario::where('tipo_usuario', 'consultor')->get();
echo "Registros por consultor:\n";
foreach ($consultores as $consultor) {
    $registros = RegistroHoras::where('usuario_id', $consultor->id)->count();
    echo "- {$consultor->primer_nombre} {$consultor->primer_apellido} (ID: {$consultor->id}): {$registros} registros\n";
    
    // Detallar por estado
    $pendientesConsultor = RegistroHoras::where('usuario_id', $consultor->id)->where('estado', 'pendiente')->count();
    $aprobadosConsultor = RegistroHoras::where('usuario_id', $consultor->id)->where('estado', 'aprobado')->count();
    $rechazadosConsultor = RegistroHoras::where('usuario_id', $consultor->id)->where('estado', 'rechazado')->count();
    
    echo "  - Pendientes: {$pendientesConsultor}\n";
    echo "  - Aprobados: {$aprobadosConsultor}\n";
    echo "  - Rechazados: {$rechazadosConsultor}\n";
}

echo "\n";

// Verificar registros por empresa
$empresas = Empresa::all();
echo "Registros por empresa:\n";
foreach ($empresas as $empresa) {
    $registros = RegistroHoras::where('empresa_id', $empresa->id)->count();
    echo "- {$empresa->nombre} (ID: {$empresa->id}): {$registros} registros\n";
}

echo "\n";

// Mostrar algunos registros de ejemplo
echo "Registros de ejemplo (últimos 5):\n";
$ejemplos = RegistroHoras::with(['usuario', 'empresa'])->orderBy('id', 'desc')->limit(5)->get();
foreach ($ejemplos as $ejemplo) {
    echo "ID: {$ejemplo->id}\n";
    echo "Consultor: {$ejemplo->usuario->primer_nombre} {$ejemplo->usuario->primer_apellido}\n";
    echo "Empresa: {$ejemplo->empresa->nombre}\n";
    echo "Fecha: {$ejemplo->fecha->format('d/m/Y')}\n";
    echo "Horas: {$ejemplo->horas_trabajadas}\n";
    echo "Estado: {$ejemplo->estado}\n";
    echo "Descripción: {$ejemplo->descripcion_actividades}\n";
    echo "---\n";
}

// Verificar si hay datos laborales para los consultores
echo "\nDatos laborales de consultores:\n";
foreach ($consultores as $consultor) {
    $datosLaborales = $consultor->datosLaborales;
    if ($datosLaborales) {
        echo "- {$consultor->primer_nombre} {$consultor->primer_apellido}: ";
        echo "Nivel: {$datosLaborales->nivel_desarrollo}, ";
        echo "Tarifa: \${$datosLaborales->tarifa_hora}\n";
    } else {
        echo "- {$consultor->primer_nombre} {$consultor->primer_apellido}: Sin datos laborales\n";
    }
}

// Verificar relaciones entre consultores y empresas
echo "\nRelaciones consultor-empresa:\n";
foreach ($consultores as $consultor) {
    $empresasAsignadas = $consultor->empresas;
    echo "- {$consultor->primer_nombre} {$consultor->primer_apellido}: ";
    if ($empresasAsignadas->isEmpty()) {
        echo "No tiene empresas asignadas\n";
    } else {
        echo count($empresasAsignadas) . " empresas asignadas: ";
        echo implode(", ", $empresasAsignadas->pluck('nombre')->toArray()) . "\n";
    }
}

// Verificar si hay problemas con las URLs
echo "\nVerificando URLs para acciones:\n";
echo "- Ver detalles: " . url('admin/horas/1') . "\n";
echo "- Aprobar: " . url('admin/horas/1/aprobar') . "\n";
echo "- Rechazar: " . url('admin/horas/1/rechazar') . "\n";