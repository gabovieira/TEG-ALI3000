<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\RegistroHoras;
use App\Models\Usuario;
use App\Models\Empresa;
use Carbon\Carbon;

echo "=== Depuración de Filtros de Horas ===\n\n";

// Simular diferentes filtros
echo "Simulando diferentes filtros para verificar resultados:\n\n";

// 1. Sin filtros
$query1 = RegistroHoras::with(['usuario', 'empresa']);
$total1 = $query1->count();
echo "1. Sin filtros: {$total1} registros\n";

// 2. Filtro por estado
$estados = ['pendiente', 'aprobado', 'rechazado'];
foreach ($estados as $estado) {
    $query2 = RegistroHoras::where('estado', $estado);
    $total2 = $query2->count();
    echo "2. Filtro por estado '{$estado}': {$total2} registros\n";
}

// 3. Filtro por consultor
$consultores = Usuario::where('tipo_usuario', 'consultor')->limit(3)->get();
foreach ($consultores as $consultor) {
    $query3 = RegistroHoras::where('usuario_id', $consultor->id);
    $total3 = $query3->count();
    echo "3. Filtro por consultor '{$consultor->primer_nombre} {$consultor->primer_apellido}': {$total3} registros\n";
}

// 4. Filtro por empresa
$empresas = Empresa::limit(3)->get();
foreach ($empresas as $empresa) {
    $query4 = RegistroHoras::where('empresa_id', $empresa->id);
    $total4 = $query4->count();
    echo "4. Filtro por empresa '{$empresa->nombre}': {$total4} registros\n";
}

// 5. Filtro por fecha
$fechaInicio = Carbon::now()->subDays(15)->format('Y-m-d');
$fechaFin = Carbon::now()->format('Y-m-d');
$query5 = RegistroHoras::whereBetween('fecha', [$fechaInicio, $fechaFin]);
$total5 = $query5->count();
echo "5. Filtro por fecha (últimos 15 días): {$total5} registros\n";

// 6. Combinación de filtros
$query6 = RegistroHoras::where('estado', 'pendiente')
                     ->where('usuario_id', $consultores->first()->id ?? 0);
$total6 = $query6->count();
echo "6. Combinación de filtros (estado 'pendiente' y primer consultor): {$total6} registros\n";

echo "\n=== Verificación de URLs ===\n\n";
echo "URL para filtrar por estado 'pendiente': " . url('/admin/horas?estado=pendiente') . "\n";
echo "URL para filtrar por consultor: " . url('/admin/horas?consultor_id=2') . "\n";
echo "URL para filtrar por empresa: " . url('/admin/horas?empresa_id=1') . "\n";
echo "URL para filtrar por fechas: " . url('/admin/horas?fecha_inicio=2025-07-01&fecha_fin=2025-07-15') . "\n";
echo "URL para combinación de filtros: " . url('/admin/horas?estado=pendiente&consultor_id=2&empresa_id=1') . "\n";

echo "\n=== Verificación Completa ===\n";