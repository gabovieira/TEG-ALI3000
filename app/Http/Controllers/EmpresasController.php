<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class EmpresasController extends Controller
{
    /**
     * Mostrar lista de empresas
     */
    public function index(Request $request)
    {
        $query = Empresa::query();
        
        // Aplicar filtros
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo_empresa', $request->tipo);
        }
        
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->has('buscar') && $request->buscar) {
            $busqueda = $request->buscar;
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('rif', 'like', "%{$busqueda}%");
            });
        }
        
        // Ordenar resultados
        $ordenCampo = $request->get('orden_campo', 'nombre');
        $ordenDireccion = $request->get('orden_dir', 'asc');
        
        $query->orderBy($ordenCampo, $ordenDireccion);
        
        // Obtener resultados paginados
        $empresas = $query->paginate(10)->withQueryString();
        
        return view('admin.empresas.index', [
            'empresas' => $empresas,
            'filtros' => $request->only(['tipo', 'estado', 'buscar']),
            'orden' => [
                'campo' => $ordenCampo,
                'direccion' => $ordenDireccion
            ]
        ]);
    }

    /**
     * Mostrar formulario para crear una nueva empresa
     */
    public function create()
    {
        $consultores = Usuario::where('tipo_usuario', 'consultor')
                            ->where('estado', 'activo')
                            ->orderBy('primer_nombre')
                            ->get();
        
        return view('admin.empresas.create', [
            'consultores' => $consultores
        ]);
    }

    /**
     * Almacenar una nueva empresa
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'rif' => 'required|string|max:20|unique:empresas,rif',
            'tipo_empresa' => 'required|in:S.A.,C.A.,Otro',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'estado' => 'required|in:activa,inactiva',
            'consultores' => 'nullable|array',
            'consultores.*' => 'exists:usuarios,id'
        ]);
        
        DB::beginTransaction();
        
        try {
            $empresa = new Empresa();
            $empresa->nombre = $request->nombre;
            $empresa->rif = $request->rif;
            $empresa->tipo_empresa = $request->tipo_empresa;
            $empresa->direccion = $request->direccion;
            $empresa->telefono = $request->telefono;
            $empresa->email = $request->email;
            $empresa->estado = $request->estado;
            $empresa->fecha_creacion = now();
            $empresa->save();
            
            // Si se seleccionaron consultores, asignarlos
            if ($request->has('consultores')) {
                foreach ($request->consultores as $consultorId) {
                    $empresa->consultores()->attach($consultorId, [
                        'fecha_asignacion' => now(),
                        'tipo_asignacion' => 'principal',
                        'estado' => 'activo',
                        'observaciones' => 'Asignación inicial'
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.empresas.index')
                ->with('success', 'Empresa creada correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear la empresa: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de una empresa específica
     */
    public function show($id)
    {
        try {
            $empresa = Empresa::with(['consultores'])->findOrFail($id);
            
            // Inicializar estadísticas con valores predeterminados
            $estadisticas = [
                'total_horas' => 0,
                'horas_pendientes' => 0,
                'total_pagos' => 0
            ];
            
            // Verificar si la tabla registro_horas existe
            if (Schema::hasTable('registro_horas')) {
                try {
                    $estadisticas['total_horas'] = DB::table('registro_horas')
                        ->where('empresa_id', $empresa->id)
                        ->where('estado', 'aprobado')
                        ->sum('horas_trabajadas');
                        
                    $estadisticas['horas_pendientes'] = DB::table('registro_horas')
                        ->where('empresa_id', $empresa->id)
                        ->where('estado', 'pendiente')
                        ->count();
                } catch (\Exception $e) {
                    // Si hay un error en la consulta, mantener los valores predeterminados
                    // y registrar el error para depuración
                    \Log::error('Error al consultar registro_horas: ' . $e->getMessage());
                }
            }
            
            // Verificar si la tabla pagos existe
            if (Schema::hasTable('pagos')) {
                try {
                    $estadisticas['total_pagos'] = DB::table('pagos')
                        ->where('empresa_id', $empresa->id)
                        ->where('estado', 'pagado')
                        ->sum('total_menos_islr_bs');
                } catch (\Exception $e) {
                    // Si hay un error en la consulta, mantener el valor predeterminado
                    // y registrar el error para depuración
                    \Log::error('Error al consultar pagos: ' . $e->getMessage());
                }
            }
            
            return view('admin.empresas.show', [
                'empresa' => $empresa,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.empresas.index')
                ->with('error', 'Error al mostrar la empresa: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar una empresa
     */
    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        $consultores = Usuario::where('tipo_usuario', 'consultor')
                            ->where('estado', 'activo')
                            ->orderBy('primer_nombre')
                            ->get();
        $consultoresAsignados = $empresa->consultores->pluck('id')->toArray();
        
        return view('admin.empresas.edit', [
            'empresa' => $empresa,
            'consultores' => $consultores,
            'consultoresAsignados' => $consultoresAsignados
        ]);
    }

    /**
     * Actualizar una empresa específica
     */
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:100',
            'rif' => ['required', 'string', 'max:20', Rule::unique('empresas')->ignore($empresa->id)],
            'tipo_empresa' => 'required|in:S.A.,C.A.,Otro',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'estado' => 'required|in:activa,inactiva',
            'consultores' => 'nullable|array',
            'consultores.*' => 'exists:usuarios,id'
        ]);
        
        DB::beginTransaction();
        
        try {
            $empresa->nombre = $request->nombre;
            $empresa->rif = $request->rif;
            $empresa->tipo_empresa = $request->tipo_empresa;
            $empresa->direccion = $request->direccion;
            $empresa->telefono = $request->telefono;
            $empresa->email = $request->email;
            $empresa->estado = $request->estado;
            $empresa->fecha_actualizacion = now();
            $empresa->save();
            
            // Actualizar consultores asignados
            $consultoresSeleccionados = $request->has('consultores') ? $request->consultores : [];
            
            // Obtener consultores actuales
            $consultoresActuales = $empresa->consultores->pluck('id')->toArray();
            
            // Nuevos consultores a asignar
            $nuevosConsultores = array_diff($consultoresSeleccionados, $consultoresActuales);
            
            // Consultores a eliminar
            $consultoresEliminar = array_diff($consultoresActuales, $consultoresSeleccionados);
            
            // Asignar nuevos consultores
            foreach ($nuevosConsultores as $consultorId) {
                $empresa->consultores()->attach($consultorId, [
                    'fecha_asignacion' => now(),
                    'tipo_asignacion' => 'principal',
                    'estado' => 'activo',
                    'observaciones' => 'Asignación desde edición de empresa'
                ]);
            }
            
            // Eliminar consultores que ya no están asignados
            foreach ($consultoresEliminar as $consultorId) {
                // Verificar si hay registros de horas para esta asignación
                $tieneHoras = false;
                
                // Verificar si la tabla registro_horas existe
                if (Schema::hasTable('registro_horas')) {
                    try {
                        $tieneHoras = DB::table('registro_horas')
                            ->where('usuario_id', $consultorId)
                            ->where('empresa_id', $empresa->id)
                            ->exists();
                    } catch (\Exception $e) {
                        \Log::error('Error al verificar registro_horas: ' . $e->getMessage());
                    }
                }
                
                if ($tieneHoras) {
                    // Si tiene horas, cambiar estado a inactivo en lugar de eliminar
                    DB::table('empresa_consultores')
                        ->where('usuario_id', $consultorId)
                        ->where('empresa_id', $empresa->id)
                        ->update(['estado' => 'inactivo']);
                } else {
                    // Si no tiene horas, eliminar la asignación
                    $empresa->consultores()->detach($consultorId);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.empresas.show', $empresa->id)
                ->with('success', 'Empresa actualizada correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la empresa: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una empresa
     */
    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        
        // Verificar si la empresa tiene registros asociados
        $tieneHoras = false;
        $tienePagos = false;
        $tieneConsultores = $empresa->consultores()->count() > 0;
        
        // Verificar si la tabla registro_horas existe
        if (Schema::hasTable('registro_horas')) {
            try {
                $tieneHoras = DB::table('registro_horas')->where('empresa_id', $empresa->id)->exists();
            } catch (\Exception $e) {
                \Log::error('Error al verificar registro_horas: ' . $e->getMessage());
            }
        }
        
        // Verificar si la tabla pagos existe
        if (Schema::hasTable('pagos')) {
            try {
                $tienePagos = DB::table('pagos')->where('empresa_id', $empresa->id)->exists();
            } catch (\Exception $e) {
                \Log::error('Error al verificar pagos: ' . $e->getMessage());
            }
        }
        
        if ($tieneHoras || $tienePagos || $tieneConsultores) {
            return back()->with('error', 
                'No se puede eliminar la empresa porque tiene registros asociados. Considere inactivarla en su lugar.');
        }
        
        try {
            // Eliminar empresa
            $empresa->delete();
            
            return redirect()->route('admin.empresas.index')
                ->with('success', 'Empresa eliminada correctamente');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la empresa: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar interfaz para asignar consultores a una empresa
     */
    public function asignarConsultores($id)
    {
        $empresa = Empresa::findOrFail($id);
        
        $consultoresAsignados = $empresa->consultores;
        $consultoresDisponibles = Usuario::where('tipo_usuario', 'consultor')
            ->where('estado', 'activo')
            ->whereNotIn('id', $consultoresAsignados->pluck('id'))
            ->orderBy('primer_nombre')
            ->get();
        
        return view('admin.empresas.asignar-consultores', [
            'empresa' => $empresa,
            'consultoresAsignados' => $consultoresAsignados,
            'consultoresDisponibles' => $consultoresDisponibles
        ]);
    }

    /**
     * Guardar asignaciones de consultores a una empresa
     */
    public function guardarAsignaciones(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        
        $request->validate([
            'consultores' => 'nullable|array',
            'consultores.*.id' => 'required|exists:usuarios,id',
            'consultores.*.tipo_asignacion' => 'required|in:tiempo_completo,parcial,temporal'
        ]);
        
        $consultoresSeleccionados = $request->has('consultores') ? $request->consultores : [];
        
        try {
            foreach ($consultoresSeleccionados as $consultorData) {
                $consultorId = $consultorData['id'];
                $tipoAsignacion = $consultorData['tipo_asignacion'];
                
                // Verificar si ya existe la asignación
                $existeAsignacion = DB::table('empresa_consultores')
                    ->where('usuario_id', $consultorId)
                    ->where('empresa_id', $empresa->id)
                    ->exists();
                
                if (!$existeAsignacion) {
                    $empresa->consultores()->attach($consultorId, [
                        'fecha_asignacion' => now(),
                        'tipo_asignacion' => $tipoAsignacion,
                        'estado' => 'activo',
                        'observaciones' => 'Asignación desde gestión de consultores'
                    ]);
                } else {
                    // Actualizar la asignación existente si es necesario
                    $empresa->consultores()->updateExistingPivot($consultorId, [
                        'tipo_asignacion' => $tipoAsignacion,
                        'estado' => 'activo',
                        'observaciones' => 'Actualización de asignación desde gestión de consultores'
                    ]);
                }
            }
            
            return redirect()->route('admin.empresas.show', $empresa->id)
                ->with('success', 'Consultores asignados correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al asignar consultores: ' . $e->getMessage());
        }
    }

/**
 * Eliminar una asignación específica entre empresa y consultor
 */
public function eliminarAsignacion($empresaId, $usuarioId)
{
    $empresa = Empresa::findOrFail($empresaId);
    
    // Verificar si hay registros de horas para esta asignación
    $tieneHoras = false;
    
    // Verificar si la tabla registro_horas existe
    if (Schema::hasTable('registro_horas')) {
        try {
            $tieneHoras = DB::table('registro_horas')
                ->where('usuario_id', $usuarioId)
                ->where('empresa_id', $empresaId)
                ->exists();
        } catch (\Exception $e) {
            \Log::error('Error al verificar registro_horas: ' . $e->getMessage());
        }
    }
    
    if ($tieneHoras) {
        return back()->with('error', 
            'No se puede eliminar la asignación porque existen registros de horas asociados a este consultor y empresa');
    }
    
    try {
        $empresa->consultores()->detach($usuarioId);
        
        return back()->with('success', 'Asignación eliminada correctamente');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error al eliminar la asignación: ' . $e->getMessage());
    }
}
}