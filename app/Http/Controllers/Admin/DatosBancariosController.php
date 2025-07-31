<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatosBancario;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

class DatosBancariosController extends Controller
{
    public function __construct()
    {
        // Los middleware se definen en las rutas, no en el controlador
    }
    
    /**
     * Mostrar datos bancarios de un consultor
     */
    public function index($consultorId)
    {
        $consultor = Usuario::where('tipo_usuario', 'consultor')
                          ->findOrFail($consultorId);
        
        $datosBancarios = DatosBancario::where('usuario_id', $consultorId)
                                     ->orderBy('es_principal', 'desc')
                                     ->get();
        
        return view('admin.datos-bancarios.index', [
            'consultor' => $consultor,
            'datosBancarios' => $datosBancarios
        ]);
    }
    
    /**
     * Mostrar formulario para crear datos bancarios
     */
    public function create($consultorId)
    {
        $consultor = Usuario::where('tipo_usuario', 'consultor')
                          ->findOrFail($consultorId);
        
        return view('admin.datos-bancarios.create', [
            'consultor' => $consultor
        ]);
    }
    
    /**
     * Guardar nuevos datos bancarios
     */
    public function store(Request $request, $consultorId)
    {
        $request->validate([
            'banco' => 'required|string|max:100',
            'tipo_cuenta' => 'required|in:ahorro,corriente',
            'numero_cuenta' => 'required|string|max:30',
            'cedula_rif' => 'required|string|max:20',
            'titular' => 'required|string|max:100',
            'es_principal' => 'sometimes|boolean',
            'correo' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'observaciones' => 'nullable|string|max:255'
        ]);
        
        try {
            // Verificar que el consultor existe
            $consultor = Usuario::where('tipo_usuario', 'consultor')
                              ->findOrFail($consultorId);
            
            // Preparar datos con valores por defecto
            $datos = [
                'usuario_id' => $consultorId,
                'banco' => $request->banco,
                'tipo_cuenta' => $request->tipo_cuenta,
                'numero_cuenta' => $request->numero_cuenta,
                'cedula_rif' => $request->cedula_rif,
                'titular' => $request->titular,
                'es_principal' => $request->boolean('es_principal', false),
                'correo' => $request->correo ?? null,
                'telefono' => $request->telefono ?? null,
                'observaciones' => $request->observaciones ?? null,
                'estado' => 'activo'
            ];
            
            // Crear el registro
            $datosBancarios = DatosBancario::create($datos);
            
            // Si es la cuenta principal, desmarcar las demás
            if ($datosBancarios->es_principal) {
                $datosBancarios->marcarComoPrincipal();
            }
            
            return redirect()->route('admin.datos-bancarios.index', $consultorId)
                           ->with('success', 'Datos bancarios guardados correctamente.');
            
        } catch (\Exception $e) {
            Log::error("Error al guardar datos bancarios: " . $e->getMessage(), [
                'consultor_id' => $consultorId,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al guardar datos bancarios: ' . $e->getMessage())
                           ->withInput();
        }
    }
    
    /**
     * Mostrar formulario para editar datos bancarios
     */
    public function edit($consultorId, $id)
    {
        $consultor = Usuario::where('tipo_usuario', 'consultor')
                          ->findOrFail($consultorId);
        
        $datosBancarios = DatosBancario::where('usuario_id', $consultorId)
                                     ->findOrFail($id);
        
        return view('admin.datos-bancarios.edit', [
            'consultor' => $consultor,
            'datosBancarios' => $datosBancarios
        ]);
    }
    
    /**
     * Actualizar datos bancarios
     */
    public function update(Request $request, $consultorId, $id)
    {
        $request->validate([
            'banco' => 'required|string|max:100',
            'tipo_cuenta' => 'required|in:ahorro,corriente',
            'numero_cuenta' => 'required|string|max:30',
            'cedula_rif' => 'required|string|max:20',
            'titular' => 'required|string|max:100',
            'es_principal' => 'boolean'
        ]);
        
        try {
            $datosBancarios = DatosBancario::where('usuario_id', $consultorId)
                                         ->findOrFail($id);
            
            $datosBancarios->banco = $request->banco;
            $datosBancarios->tipo_cuenta = $request->tipo_cuenta;
            $datosBancarios->numero_cuenta = $request->numero_cuenta;
            $datosBancarios->cedula_rif = $request->cedula_rif;
            $datosBancarios->titular = $request->titular;
            $datosBancarios->es_principal = $request->has('es_principal') ? true : false;
            
            $datosBancarios->save();
            
            // Si es la cuenta principal, desmarcar las demás
            if ($datosBancarios->es_principal) {
                $datosBancarios->establecerComoPrincipal();
            }
            
            return redirect()->route('admin.datos-bancarios.index', $consultorId)
                           ->with('success', 'Datos bancarios actualizados correctamente.');
            
        } catch (\Exception $e) {
            Log::error("Error al actualizar datos bancarios: " . $e->getMessage(), [
                'consultor_id' => $consultorId,
                'datos_bancarios_id' => $id,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al actualizar datos bancarios: ' . $e->getMessage())
                           ->withInput();
        }
    }
    
    /**
     * Establecer cuenta bancaria como principal
     */
    public function establecerPrincipal($consultorId, $id)
    {
        try {
            $datosBancarios = DatosBancario::where('usuario_id', $consultorId)
                                         ->findOrFail($id);
            
            $datosBancarios->establecerComoPrincipal();
            
            return redirect()->route('admin.datos-bancarios.index', $consultorId)
                           ->with('success', 'Cuenta establecida como principal.');
            
        } catch (\Exception $e) {
            Log::error("Error al establecer cuenta principal: " . $e->getMessage(), [
                'consultor_id' => $consultorId,
                'datos_bancarios_id' => $id,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al establecer cuenta principal: ' . $e->getMessage());
        }
    }
    
    /**
     * Eliminar datos bancarios
     */
    public function destroy($consultorId, $id)
    {
        try {
            $datosBancarios = DatosBancario::where('usuario_id', $consultorId)
                                         ->findOrFail($id);
            
            // Verificar si hay pagos asociados a estos datos bancarios
            $pagosAsociados = \App\Models\Pago::where('datos_bancarios_id', $id)->exists();
            
            if ($pagosAsociados) {
                return redirect()->back()
                               ->with('error', 'No se puede eliminar esta cuenta porque hay pagos asociados a ella.');
            }
            
            $datosBancarios->delete();
            
            return redirect()->route('admin.datos-bancarios.index', $consultorId)
                           ->with('success', 'Datos bancarios eliminados correctamente.');
            
        } catch (\Exception $e) {
            Log::error("Error al eliminar datos bancarios: " . $e->getMessage(), [
                'consultor_id' => $consultorId,
                'datos_bancarios_id' => $id,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al eliminar datos bancarios: ' . $e->getMessage());
        }
    }
    
    /**
     * Validar formato de número de cuenta (AJAX)
     */
    public function validarNumeroCuenta(Request $request)
    {
        $request->validate([
            'banco' => 'required|string',
            'numero_cuenta' => 'required|string'
        ]);
        
        try {
            $banco = $request->banco;
            $numeroCuenta = $request->numero_cuenta;
            $esValido = true;
            $mensaje = '';
            
            // Validar según el banco
            switch ($banco) {
                case 'Banco de Venezuela':
                    // Ejemplo: 20 dígitos numéricos
                    $esValido = preg_match('/^\d{20}$/', $numeroCuenta);
                    $mensaje = $esValido ? 'Formato válido' : 'El número de cuenta debe tener 20 dígitos numéricos';
                    break;
                    
                case 'Banesco':
                    // Ejemplo: 20 dígitos numéricos
                    $esValido = preg_match('/^\d{20}$/', $numeroCuenta);
                    $mensaje = $esValido ? 'Formato válido' : 'El número de cuenta debe tener 20 dígitos numéricos';
                    break;
                    
                case 'Mercantil':
                    // Ejemplo: 20 dígitos numéricos
                    $esValido = preg_match('/^\d{20}$/', $numeroCuenta);
                    $mensaje = $esValido ? 'Formato válido' : 'El número de cuenta debe tener 20 dígitos numéricos';
                    break;
                    
                default:
                    // Validación genérica: entre 15 y 20 dígitos numéricos
                    $esValido = preg_match('/^\d{15,20}$/', $numeroCuenta);
                    $mensaje = $esValido ? 'Formato válido' : 'El número de cuenta debe tener entre 15 y 20 dígitos numéricos';
                    break;
            }
            
            return response()->json([
                'success' => true,
                'valido' => $esValido,
                'mensaje' => $mensaje
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al validar número de cuenta: ' . $e->getMessage()
            ], 500);
        }
    }
}
