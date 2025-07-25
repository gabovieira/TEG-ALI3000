<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConfiguracionController extends Controller
{
    // Constructor removido - middlewares aplicados en rutas

    /**
     * Mostrar la página principal de configuraciones
     */
    public function index(Request $request)
    {
        $categoria = $request->get('categoria', 'all');
        
        $query = Configuracion::with('actualizadoPor');
        
        if ($categoria !== 'all') {
            $query->where('categoria', $categoria);
        }
        
        $configuraciones = $query->orderBy('categoria')
                                ->orderBy('clave')
                                ->get()
                                ->groupBy('categoria');
        
        $categorias = Configuracion::distinct()
                                  ->pluck('categoria')
                                  ->sort();
        
        return view('admin.configuracion.index', compact('configuraciones', 'categorias', 'categoria'));
    }

    /**
     * Mostrar formulario para editar una configuración específica
     */
    public function edit($id)
    {
        $configuracion = Configuracion::findOrFail($id);
        
        return view('admin.configuracion.edit', compact('configuracion'));
    }

    /**
     * Actualizar una configuración específica
     */
    public function update(Request $request, $id)
    {
        $configuracion = Configuracion::findOrFail($id);
        
        $rules = [
            'valor' => 'required',
            'descripcion' => 'nullable|string|max:500'
        ];
        
        // Validaciones específicas según el tipo
        switch ($configuracion->tipo) {
            case 'numero':
                $rules['valor'] = 'required|numeric';
                break;
            case 'booleano':
                $rules['valor'] = 'required|in:0,1,true,false';
                break;
        }
        
        $request->validate($rules);
        
        try {
            DB::beginTransaction();
            
            $configuracion->update([
                'valor' => $request->valor,
                'descripcion' => $request->descripcion,
                'actualizado_por' => Auth::id(),
                'fecha_actualizacion' => now()
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('admin.configuracion.index')
                ->with('success', 'Configuración actualizada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar la configuración: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar múltiples configuraciones por categoría
     */
    public function updateCategoria(Request $request)
    {
        $categoria = $request->get('categoria');
        $configuraciones = $request->get('configuraciones', []);
        
        if (empty($configuraciones)) {
            return redirect()
                ->back()
                ->with('error', 'No se enviaron configuraciones para actualizar');
        }
        
        try {
            DB::beginTransaction();
            
            foreach ($configuraciones as $id => $datos) {
                $configuracion = Configuracion::find($id);
                
                if ($configuracion) {
                    // Validar según el tipo
                    $valor = $datos['valor'];
                    
                    switch ($configuracion->tipo) {
                        case 'numero':
                            if (!is_numeric($valor)) {
                                throw new \Exception("El valor para {$configuracion->clave} debe ser numérico");
                            }
                            break;
                        case 'booleano':
                            $valor = in_array($valor, ['1', 'true', true]) ? '1' : '0';
                            break;
                    }
                    
                    $configuracion->update([
                        'valor' => $valor,
                        'actualizado_por' => Auth::id(),
                        'fecha_actualizacion' => now()
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.configuracion.index', ['categoria' => $categoria])
                ->with('success', 'Configuraciones actualizadas exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar las configuraciones: ' . $e->getMessage());
        }
    }

    /**
     * Restablecer configuraciones a valores por defecto
     */
    public function reset(Request $request)
    {
        $categoria = $request->get('categoria');
        
        // Aquí podrías implementar la lógica para restablecer valores por defecto
        // Por ahora solo mostramos un mensaje
        
        return redirect()
            ->route('admin.configuracion.index', ['categoria' => $categoria])
            ->with('info', 'Funcionalidad de restablecimiento en desarrollo');
    }

    /**
     * API para obtener configuraciones (para uso interno)
     */
    public function api(Request $request)
    {
        $categoria = $request->get('categoria');
        $clave = $request->get('clave');
        
        $query = Configuracion::query();
        
        if ($categoria) {
            $query->where('categoria', $categoria);
        }
        
        if ($clave) {
            $query->where('clave', $clave);
        }
        
        $configuraciones = $query->get()->mapWithKeys(function ($config) {
            return [$config->clave => $config->valor_formateado];
        });
        
        return response()->json($configuraciones);
    }
}