<?php

namespace App\Http\Controllers\Consultor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistroHoras;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RegistroHorasController extends Controller
{
    /**
     * Mostrar lista de horas registradas
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        
        // Filtros
        $estado = $request->estado;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $empresaId = $request->empresa_id;
        
        // Consulta base
        $query = RegistroHoras::where('usuario_id', $usuario->id)
                             ->with('empresa')
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
        
        // Obtener resultados paginados
        $registros = $query->paginate(10);
        
        // Obtener empresas para el filtro
        $empresas = $usuario->empresas;
        
        return view('consultor.horas.index', [
            'registros' => $registros,
            'empresas' => $empresas,
            'filtros' => [
                'estado' => $estado,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'empresa_id' => $empresaId
            ]
        ]);
    }

    /**
     * Mostrar formulario para crear nuevo registro
     */
    public function create()
    {
        $usuario = Auth::user();
        $empresas = $usuario->empresas;
        $fechaActual = Carbon::now()->format('Y-m-d');
        
        return view('consultor.horas.create', [
            'empresas' => $empresas,
            'fechaActual' => $fechaActual
        ]);
    }

    /**
     * Almacenar nuevo registro de horas
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();
        
        // Validar datos
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'fecha' => 'required|date|before_or_equal:today',
            'horas_trabajadas' => 'required|numeric|between:0.5,12',
            'descripcion_actividades' => 'required|string|max:500'
        ]);
        
        // Verificar que el consultor esté asignado a la empresa
        $empresaAsignada = $usuario->empresas()
            ->where('empresas.id', $request->empresa_id)
            ->exists();
            
        if (!$empresaAsignada) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'No estás asignado a esta empresa');
        }
        
        // Verificar que no exceda el límite diario de horas
        $horasExistentes = RegistroHoras::where('usuario_id', $usuario->id)
                                      ->where('fecha', $request->fecha)
                                      ->sum('horas_trabajadas');
                                      
        $totalHoras = $horasExistentes + $request->horas_trabajadas;
        
        if ($totalHoras > 12) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'No puedes registrar más de 12 horas en un día. Ya tienes ' . $horasExistentes . ' horas registradas.');
        }
        
        // Crear registro
        $registro = new RegistroHoras();
        $registro->usuario_id = $usuario->id;
        $registro->empresa_id = $request->empresa_id;
        $registro->fecha = $request->fecha;
        $registro->horas_trabajadas = $request->horas_trabajadas;
        $registro->descripcion_actividades = $request->descripcion_actividades;
        $registro->estado = 'pendiente';
        $registro->tipo_registro = 'manual';
        $registro->save();
        
        return redirect()->route('consultor.horas.index')
                       ->with('success', 'Horas registradas correctamente. Pendiente de aprobación.');
    }

    /**
     * Mostrar formulario para editar registro
     */
    public function edit($id)
    {
        $usuario = Auth::user();
        $registro = RegistroHoras::where('id', $id)
                               ->where('usuario_id', $usuario->id)
                               ->firstOrFail();
        
        // Verificar que el registro esté pendiente
        if (!$registro->puedeEditar()) {
            return redirect()->route('consultor.horas.index')
                           ->with('error', 'No puedes editar un registro que ya ha sido aprobado o rechazado');
        }
        
        $empresas = $usuario->empresas;
        
        return view('consultor.horas.edit', [
            'registro' => $registro,
            'empresas' => $empresas
        ]);
    }

    /**
     * Actualizar registro de horas
     */
    public function update(Request $request, $id)
    {
        $usuario = Auth::user();
        $registro = RegistroHoras::where('id', $id)
                               ->where('usuario_id', $usuario->id)
                               ->firstOrFail();
        
        // Verificar que el registro esté pendiente
        if (!$registro->puedeEditar()) {
            return redirect()->route('consultor.horas.index')
                           ->with('error', 'No puedes editar un registro que ya ha sido aprobado o rechazado');
        }
        
        // Validar datos
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'fecha' => 'required|date|before_or_equal:today',
            'horas_trabajadas' => 'required|numeric|between:0.5,12',
            'descripcion_actividades' => 'required|string|max:500'
        ]);
        
        // Verificar que el consultor esté asignado a la empresa
        $empresaAsignada = $usuario->empresas()
            ->where('empresas.id', $request->empresa_id)
            ->exists();
            
        if (!$empresaAsignada) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'No estás asignado a esta empresa');
        }
        
        // Verificar que no exceda el límite diario de horas
        $horasExistentes = RegistroHoras::where('usuario_id', $usuario->id)
                                      ->where('fecha', $request->fecha)
                                      ->where('id', '!=', $id)
                                      ->sum('horas_trabajadas');
                                      
        $totalHoras = $horasExistentes + $request->horas_trabajadas;
        
        if ($totalHoras > 12) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'No puedes registrar más de 12 horas en un día. Ya tienes ' . $horasExistentes . ' horas registradas.');
        }
        
        // Actualizar registro
        $registro->empresa_id = $request->empresa_id;
        $registro->fecha = $request->fecha;
        $registro->horas_trabajadas = $request->horas_trabajadas;
        $registro->descripcion_actividades = $request->descripcion_actividades;
        $registro->save();
        
        return redirect()->route('consultor.horas.index')
                       ->with('success', 'Registro actualizado correctamente');
    }

    /**
     * Eliminar registro de horas
     */
    public function destroy($id)
    {
        $usuario = Auth::user();
        $registro = RegistroHoras::where('id', $id)
                               ->where('usuario_id', $usuario->id)
                               ->firstOrFail();
        
        // Verificar que el registro esté pendiente
        if (!$registro->puedeEliminar()) {
            return redirect()->route('consultor.horas.index')
                           ->with('error', 'No puedes eliminar un registro que ya ha sido aprobado o rechazado');
        }
        
        $registro->delete();
        
        return redirect()->route('consultor.horas.index')
                       ->with('success', 'Registro eliminado correctamente');
    }

    /**
     * Mostrar detalles de un registro
     */
    public function show($id)
    {
        $usuario = Auth::user();
        $registro = RegistroHoras::where('id', $id)
                               ->where('usuario_id', $usuario->id)
                               ->with(['empresa', 'aprobador'])
                               ->firstOrFail();
        
        return view('consultor.horas.show', [
            'registro' => $registro
        ]);
    }
}