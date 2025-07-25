<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistroHoras;
use App\Models\Usuario;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HorasController extends Controller
{
    /**
     * Mostrar lista de horas registradas por los consultores
     */
    public function index(Request $request)
    {
        // Filtros
        $estado = $request->estado; // No establecer un valor por defecto para mostrar todos los estados
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $empresaId = $request->empresa_id;
        $consultorId = $request->consultor_id;
        
        // Log para depuración
        \Log::info('Filtros aplicados:', [
            'estado' => $estado,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'empresa_id' => $empresaId,
            'consultor_id' => $consultorId,
        ]);
        
        // Consulta base para la vista tradicional
        $query = RegistroHoras::with(['usuario', 'usuario.datosLaborales', 'empresa'])
                             ->orderBy('fecha', 'desc');
        
        // Aplicar filtros si existen
        if ($estado) {
            $query->where('estado', $estado);
        }
        
        if ($fechaInicio) {
            $query->where('fecha', '>=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->where('fecha', '<=', $fechaFin);
        }
        
        if ($empresaId) {
            $query->where('empresa_id', $empresaId);
        }
        
        if ($consultorId) {
            $query->where('usuario_id', $consultorId);
        }
        
        // Obtener resultados paginados
        $registros = $query->paginate(15);
        
        // Obtener empresas y consultores para los filtros
        $empresas = Empresa::where('estado', 'activa')->get();
        $consultores = Usuario::where('tipo_usuario', 'consultor')
                            ->where('estado', 'activo')
                            ->get();
        
        return view('admin.horas.index', [
            'registros' => $registros,
            'empresas' => $empresas,
            'consultores' => $consultores,
            'filtros' => [
                'estado' => $estado,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'empresa_id' => $empresaId,
                'consultor_id' => $consultorId
            ]
        ]);
    }

    /**
     * Mostrar detalles de un registro de horas
     */
    public function show($id)
    {
        $registro = RegistroHoras::with(['usuario', 'empresa', 'aprobador'])
                               ->findOrFail($id);
        
        return view('admin.horas.show', [
            'registro' => $registro
        ]);
    }

    /**
     * Aprobar un registro de horas
     */
    public function aprobar($id)
    {
        $registro = RegistroHoras::findOrFail($id);
        
        // Verificar que el registro esté pendiente
        if ($registro->estado !== 'pendiente') {
            return redirect()->back()
                           ->with('error', 'Solo se pueden aprobar registros pendientes');
        }
        
        // Actualizar el registro
        $registro->estado = 'aprobado';
        $registro->aprobado_por = Auth::id();
        $registro->fecha_aprobacion = now();
        $registro->save();
        
        return redirect()->back()
                       ->with('success', 'Registro aprobado correctamente');
    }

    /**
     * Rechazar un registro de horas
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|max:500'
        ]);
        
        $registro = RegistroHoras::findOrFail($id);
        
        // Verificar que el registro esté pendiente
        if ($registro->estado !== 'pendiente') {
            return redirect()->back()
                           ->with('error', 'Solo se pueden rechazar registros pendientes');
        }
        
        // Actualizar el registro
        $registro->estado = 'rechazado';
        $registro->aprobado_por = Auth::id();
        $registro->fecha_aprobacion = now();
        $registro->motivo_rechazo = $request->motivo_rechazo;
        $registro->save();
        
        return redirect()->back()
                       ->with('success', 'Registro rechazado correctamente');
    }

    /**
     * Aprobar múltiples registros de horas
     */
    public function aprobarMultiple(Request $request)
    {
        $request->validate([
            'registros' => 'required|array',
            'registros.*' => 'exists:registro_horas,id'
        ]);
        
        $contador = 0;
        
        foreach ($request->registros as $id) {
            $registro = RegistroHoras::where('id', $id)
                                   ->where('estado', 'pendiente')
                                   ->first();
            
            if ($registro) {
                $registro->estado = 'aprobado';
                $registro->aprobado_por = Auth::id();
                $registro->fecha_aprobacion = now();
                $registro->save();
                
                $contador++;
            }
        }
        
        return redirect()->back()
                       ->with('success', "Se han aprobado {$contador} registros correctamente");
    }

    /**
     * Rechazar múltiples registros de horas
     */
    public function rechazarMultiple(Request $request)
    {
        $request->validate([
            'registros' => 'required|array',
            'registros.*' => 'exists:registro_horas,id',
            'motivo_rechazo' => 'required|string|max:500'
        ]);
        
        $contador = 0;
        
        foreach ($request->registros as $id) {
            $registro = RegistroHoras::where('id', $id)
                                   ->where('estado', 'pendiente')
                                   ->first();
            
            if ($registro) {
                $registro->estado = 'rechazado';
                $registro->aprobado_por = Auth::id();
                $registro->fecha_aprobacion = now();
                $registro->motivo_rechazo = $request->motivo_rechazo;
                $registro->save();
                
                $contador++;
            }
        }
        
        return redirect()->back()
                       ->with('success', "Se han rechazado {$contador} registros correctamente");
    }
}