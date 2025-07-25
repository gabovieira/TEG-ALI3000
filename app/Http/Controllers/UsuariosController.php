<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class UsuariosController extends Controller
{
    /**
     * Mostrar lista de usuarios
     */
    public function index(Request $request)
    {
        $query = Usuario::query();
        
        // Aplicar filtros
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo_usuario', $request->tipo);
        }
        
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->has('buscar') && $request->buscar) {
            $busqueda = $request->buscar;
            $query->where(function($q) use ($busqueda) {
                $q->where('primer_nombre', 'like', "%{$busqueda}%")
                  ->orWhere('primer_apellido', 'like', "%{$busqueda}%")
                  ->orWhere('email', 'like', "%{$busqueda}%");
            });
        }
        
        // Ordenar resultados
        $ordenCampo = $request->get('orden_campo', 'primer_nombre');
        $ordenDireccion = $request->get('orden_dir', 'asc');
        
        $query->orderBy($ordenCampo, $ordenDireccion);
        
        // Obtener resultados paginados
        $usuarios = $query->paginate(10)->withQueryString();
        
        return view('admin.usuarios.index', [
            'usuarios' => $usuarios,
            'filtros' => $request->only(['tipo', 'estado', 'buscar']),
            'orden' => [
                'campo' => $ordenCampo,
                'direccion' => $ordenDireccion
            ]
        ]);
    }

    /**
     * Mostrar formulario para crear un nuevo usuario
     */
    public function create()
    {
        $empresas = Empresa::where('estado', 'activa')->orderBy('nombre')->get();
        
        return view('admin.usuarios.create', [
            'empresas' => $empresas
        ]);
    }

    /**
     * Almacenar un nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'tipo_usuario' => 'required|in:administrador,consultor',
            'estado' => 'required|in:activo,inactivo,pendiente_registro',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|min:8',
            'empresas' => 'nullable|array',
            'empresas.*' => 'exists:empresas,id'
        ]);
        
        DB::beginTransaction();
        
        try {
            $usuario = new Usuario();
            $usuario->primer_nombre = $request->nombre;
            $usuario->primer_apellido = $request->apellido;
            $usuario->email = $request->email;
            $usuario->tipo_usuario = $request->tipo_usuario;
            $usuario->estado = $request->estado;
            $usuario->telefono = $request->telefono;
            $usuario->password_hash = Hash::make($request->password);
            $usuario->fecha_creacion = now();
            $usuario->save();
            
            // Si es consultor y se seleccionaron empresas, asignarlas
            if ($request->tipo_usuario == 'consultor' && $request->has('empresas')) {
                $usuario->empresas()->attach($request->empresas);
            }
            
            DB::commit();
            
            return redirect()->route('admin.usuarios.index')
                ->with('success', 'Usuario creado correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un usuario específico
     */
    public function show($id)
    {
        try {
            $usuario = Usuario::with(['empresas'])->findOrFail($id);
            
            // Inicializar estadísticas con valores predeterminados
            $estadisticas = [
                'total_horas' => 0,
                'total_pagos' => 0,
                'horas_pendientes' => 0
            ];
            
            // Obtener estadísticas para consultores
            if ($usuario->tipo_usuario == 'consultor') {
                // Verificar si la tabla registro_horas existe
                if (Schema::hasTable('registro_horas')) {
                    try {
                        $estadisticas['total_horas'] = DB::table('registro_horas')
                            ->where('usuario_id', $usuario->id)
                            ->where('estado', 'aprobado')
                            ->sum('horas_trabajadas'); // Cambiado de total_horas a horas_trabajadas
                            
                        $estadisticas['horas_pendientes'] = DB::table('registro_horas')
                            ->where('usuario_id', $usuario->id)
                            ->where('estado', 'pendiente')
                            ->count();
                    } catch (\Exception $e) {
                        // Si hay un error en la consulta, mantener los valores predeterminados
                        \Log::error('Error al consultar registro_horas: ' . $e->getMessage());
                    }
                }
                
                // Verificar si la tabla pagos existe
                if (Schema::hasTable('pagos')) {
                    try {
                        $estadisticas['total_pagos'] = DB::table('pagos')
                            ->where('usuario_id', $usuario->id)
                            ->where('estado', 'pagado')
                            ->sum('total_menos_islr_divisas');
                    } catch (\Exception $e) {
                        // Si hay un error en la consulta, mantener el valor predeterminado
                        \Log::error('Error al consultar pagos: ' . $e->getMessage());
                    }
                }
            }
            
            return view('admin.usuarios.show', [
                'usuario' => $usuario,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'Error al mostrar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar un usuario
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $empresas = Empresa::where('estado', 'activa')->orderBy('nombre')->get();
        $empresasAsignadas = [];
        
        if ($usuario->tipo_usuario == 'consultor') {
            $empresasAsignadas = $usuario->empresas->pluck('id')->toArray();
        }
        
        return view('admin.usuarios.edit', [
            'usuario' => $usuario,
            'empresas' => $empresas,
            'empresasAsignadas' => $empresasAsignadas
        ]);
    }

    /**
     * Actualizar un usuario específico
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('usuarios')->ignore($usuario->id)],
            'tipo_usuario' => 'required|in:administrador,consultor',
            'estado' => 'required|in:activo,inactivo,pendiente_registro',
            'telefono' => 'nullable|string|max:20',
            'password' => 'nullable|min:8',
            'empresas' => 'nullable|array',
            'empresas.*' => 'exists:empresas,id'
        ]);
        
        DB::beginTransaction();
        
        try {
            $usuario->primer_nombre = $request->nombre;
            $usuario->primer_apellido = $request->apellido;
            $usuario->email = $request->email;
            $usuario->tipo_usuario = $request->tipo_usuario;
            $usuario->estado = $request->estado;
            $usuario->telefono = $request->telefono;
            
            if ($request->filled('password')) {
                $usuario->password_hash = Hash::make($request->password);
            }
            
            $usuario->save();
            
            // Actualizar empresas asignadas si es consultor
            if ($usuario->tipo_usuario == 'consultor') {
                $empresasSeleccionadas = $request->has('empresas') ? $request->empresas : [];
                $usuario->empresas()->sync($empresasSeleccionadas);
            } else {
                // Si no es consultor, eliminar todas las asignaciones de empresas
                $usuario->empresas()->detach();
            }
            
            DB::commit();
            
            return redirect()->route('admin.usuarios.show', $usuario->id)
                ->with('success', 'Usuario actualizado correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un usuario
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        // Verificar si el usuario tiene registros asociados
        $tieneHoras = false;
        $tienePagos = false;
        
        // Verificar si la tabla registro_horas existe
        if (Schema::hasTable('registro_horas')) {
            try {
                $tieneHoras = DB::table('registro_horas')->where('usuario_id', $usuario->id)->exists();
            } catch (\Exception $e) {
                \Log::error('Error al verificar registro_horas: ' . $e->getMessage());
            }
        }
        
        // Verificar si la tabla pagos existe
        if (Schema::hasTable('pagos')) {
            try {
                $tienePagos = DB::table('pagos')->where('usuario_id', $usuario->id)->exists();
            } catch (\Exception $e) {
                \Log::error('Error al verificar pagos: ' . $e->getMessage());
            }
        }
        
        if ($tieneHoras || $tienePagos) {
            return back()->with('error', 
                'No se puede eliminar el usuario porque tiene registros asociados. Considere inactivarlo en su lugar.');
        }
        
        try {
            // Eliminar asignaciones de empresas
            $usuario->empresas()->detach();
            
            // Eliminar usuario
            $usuario->delete();
            
            return redirect()->route('admin.usuarios.index')
                ->with('success', 'Usuario eliminado correctamente');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar interfaz para asignar empresas a un consultor
     */
    public function asignarEmpresas($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        if ($usuario->tipo_usuario != 'consultor') {
            return redirect()->route('admin.usuarios.show', $usuario->id)
                ->with('error', 'Solo se pueden asignar empresas a consultores');
        }
        
        $empresasAsignadas = $usuario->empresas;
        $empresasDisponibles = Empresa::where('estado', 'activa')
            ->whereNotIn('id', $empresasAsignadas->pluck('id'))
            ->orderBy('nombre')
            ->get();
        
        return view('admin.usuarios.asignar-empresas', [
            'usuario' => $usuario,
            'empresasAsignadas' => $empresasAsignadas,
            'empresasDisponibles' => $empresasDisponibles
        ]);
    }

    /**
     * Guardar asignaciones de empresas a un consultor
     */
    public function guardarAsignaciones(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        
        if ($usuario->tipo_usuario != 'consultor') {
            return redirect()->route('admin.usuarios.show', $usuario->id)
                ->with('error', 'Solo se pueden asignar empresas a consultores');
        }
        
        $request->validate([
            'empresas' => 'nullable|array',
            'empresas.*' => 'exists:empresas,id'
        ]);
        
        $empresasSeleccionadas = $request->has('empresas') ? $request->empresas : [];
        
        try {
            $usuario->empresas()->sync($empresasSeleccionadas);
            
            return redirect()->route('admin.usuarios.show', $usuario->id)
                ->with('success', 'Empresas asignadas correctamente');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al asignar empresas: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una asignación específica entre consultor y empresa
     */
    public function eliminarAsignacion($usuarioId, $empresaId)
    {
        $usuario = Usuario::findOrFail($usuarioId);
        
        if ($usuario->tipo_usuario != 'consultor') {
            return redirect()->route('admin.usuarios.show', $usuario->id)
                ->with('error', 'Solo se pueden gestionar empresas para consultores');
        }
        
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
                'No se puede eliminar la asignación porque existen registros de horas asociados a esta empresa y consultor');
        }
        
        try {
            $usuario->empresas()->detach($empresaId);
            
            return back()->with('success', 'Asignación eliminada correctamente');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la asignación: ' . $e->getMessage());
        }
    }
}