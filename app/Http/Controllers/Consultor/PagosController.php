<?php

namespace App\Http\Controllers\Consultor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\DatosBancario;
use App\Services\PagoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PagosController extends Controller
{
    protected $pagoService;
    
    public function __construct(PagoService $pagoService)
    {
        $this->pagoService = $pagoService;
        // Los middleware se definen en las rutas, no en el controlador
    }
    
    /**
     * Mostrar lista de pagos del consultor
     */
    public function index(Request $request)
    {
        $usuarioId = Auth::id();
        $estado = $request->estado;
        
        // Consulta base
        $query = Pago::with(['detalles.empresa', 'procesador', 'datosBancarios'])
                    ->where('usuario_id', $usuarioId)
                    ->orderBy('created_at', 'desc');
        
        // Aplicar filtro de estado si existe
        if ($estado) {
            $query->where('estado', $estado);
        }
        
        // Obtener resultados paginados
        $pagos = $query->paginate(10);
        
        // Obtener contadores para el dashboard
        $pendientesConfirmacion = Pago::where('usuario_id', $usuarioId)
                                    ->where('estado', 'pagado')
                                    ->count();
        
        $confirmados = Pago::where('usuario_id', $usuarioId)
                         ->where('estado', 'confirmado')
                         ->count();
        
        $totalPagos = Pago::where('usuario_id', $usuarioId)
                        ->whereIn('estado', ['pendiente', 'pagado', 'confirmado'])
                        ->count();
        
        return view('consultor.pagos.index', [
            'pagos' => $pagos,
            'pendientesConfirmacion' => $pendientesConfirmacion,
            'confirmados' => $confirmados,
            'totalPagos' => $totalPagos,
            'filtroEstado' => $estado
        ]);
    }
    
    /**
     * Mostrar detalle de un pago
     */
    public function show($id)
    {
        $usuarioId = Auth::id();
        
        $pago = Pago::with(['detalles.empresa', 'procesador', 'datosBancarios'])
                   ->where('usuario_id', $usuarioId)
                   ->findOrFail($id);
        
        return view('consultor.pagos.show', [
            'pago' => $pago
        ]);
    }
    
    /**
     * Mostrar formulario para confirmar recepción de pago
     */
    public function mostrarConfirmar($id)
    {
        $usuarioId = Auth::id();
        
        $pago = Pago::with(['detalles.empresa', 'procesador', 'datosBancarios'])
                   ->where('usuario_id', $usuarioId)
                   ->where('estado', 'pagado')
                   ->findOrFail($id);
        
        return view('consultor.pagos.confirmar', [
            'pago' => $pago
        ]);
    }
    
    /**
     * Confirmar recepción de pago
     */
    public function confirmar(Request $request, $id)
    {
        $request->validate([
            'accion' => 'required|in:confirmar,rechazar',
            'comentario' => 'nullable|string|max:500'
        ]);
        
        $usuarioId = Auth::id();
        
        try {
            // Verificar que el pago pertenece al consultor
            $pago = Pago::where('usuario_id', $usuarioId)
                       ->where('estado', 'pagado')
                       ->findOrFail($id);
            
            if ($request->accion === 'confirmar') {
                // Confirmar recepción
                $this->pagoService->confirmarRecepcionPago($id, $request->comentario);
                
                return redirect()->route('consultor.pagos.index')
                               ->with('success', 'Has confirmado la recepción del pago correctamente.');
            } else {
                // Rechazar pago
                $pago->rechazarPago($request->comentario);
                
                // Notificar al administrador
                if ($pago->procesador) {
                    // Aquí puedes implementar la notificación
                    Log::info("Pago rechazado por consultor", [
                        'pago_id' => $pago->id,
                        'consultor_id' => $usuarioId,
                        'motivo' => $request->comentario
                    ]);
                }
                
                return redirect()->route('consultor.pagos.index')
                               ->with('success', 'Has reportado un problema con el pago. Un administrador revisará tu caso.');
            }
            
        } catch (\Exception $e) {
            Log::error("Error al procesar confirmación de pago: " . $e->getMessage(), [
                'pago_id' => $id,
                'usuario_id' => $usuarioId,
                'accion' => $request->accion,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al procesar la confirmación: ' . $e->getMessage());
        }
    }
    
    /**
     * Rechazar pago
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);
        
        $usuarioId = Auth::id();
        
        try {
            // Verificar que el pago pertenece al consultor
            $pago = Pago::where('usuario_id', $usuarioId)
                       ->where('estado', 'procesado')
                       ->findOrFail($id);
            
            // Rechazar pago
            $pago->rechazarPago($request->motivo);
            
            // Notificar al administrador
            if ($pago->procesador) {
                $pago->procesador->notify(new \App\Notifications\PagoRechazadoNotification($pago));
            }
            
            return redirect()->route('consultor.pagos.index')
                           ->with('success', 'Has reportado un problema con el pago. Un administrador revisará tu caso.');
            
        } catch (\Exception $e) {
            Log::error("Error al rechazar pago: " . $e->getMessage(), [
                'pago_id' => $id,
                'usuario_id' => $usuarioId,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al reportar problema: ' . $e->getMessage());
        }
    }
    
    /**
     * Descargar comprobante de pago
     */
    public function descargarComprobante($id)
    {
        $usuarioId = Auth::id();
        
        try {
            // Verificar que el pago pertenece al consultor
            $pago = Pago::where('usuario_id', $usuarioId)
                       ->findOrFail($id);
            
            return $this->pagoService->descargarComprobante($id);
            
        } catch (\Exception $e) {
            Log::error("Error al descargar comprobante: " . $e->getMessage(), [
                'pago_id' => $id,
                'usuario_id' => $usuarioId,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al descargar comprobante: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar y gestionar datos bancarios
     */
    public function datosBancarios()
    {
        return redirect()->route('consultor.datos-bancarios.index');
    }
    
    /**
     * Guardar nuevos datos bancarios
     */
    public function guardarDatosBancarios(Request $request)
    {
        $request->validate([
            'banco' => 'required|string|max:100',
            'tipo_cuenta' => 'required|in:ahorro,corriente',
            'numero_cuenta' => 'required|string|max:30',
            'cedula_rif' => 'required|string|max:20',
            'titular' => 'required|string|max:100',
            'es_principal' => 'boolean'
        ]);
        
        $usuarioId = Auth::id();
        
        try {
            $datosBancarios = new DatosBancario([
                'usuario_id' => $usuarioId,
                'banco' => $request->banco,
                'tipo_cuenta' => $request->tipo_cuenta,
                'numero_cuenta' => $request->numero_cuenta,
                'cedula_rif' => $request->cedula_rif,
                'titular' => $request->titular,
                'es_principal' => $request->has('es_principal') ? true : false
            ]);
            
            $datosBancarios->save();
            
            // Si es la cuenta principal, desmarcar las demás
            if ($datosBancarios->es_principal) {
                $datosBancarios->establecerComoPrincipal();
            }
            
            return redirect()->route('consultor.datos-bancarios.index')
                           ->with('success', 'Datos bancarios guardados correctamente.');
            
        } catch (\Exception $e) {
            Log::error("Error al guardar datos bancarios: " . $e->getMessage(), [
                'usuario_id' => $usuarioId,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al guardar datos bancarios: ' . $e->getMessage())
                           ->withInput();
        }
    }
    
    /**
     * Establecer cuenta bancaria como principal
     */
    public function establecerPrincipal($id)
    {
        $usuarioId = Auth::id();
        
        try {
            $datosBancarios = DatosBancario::where('usuario_id', $usuarioId)
                                         ->findOrFail($id);
            
            $datosBancarios->establecerComoPrincipal();
            
            return redirect()->route('consultor.datos-bancarios.index')
                           ->with('success', 'Cuenta establecida como principal.');
            
        } catch (\Exception $e) {
            Log::error("Error al establecer cuenta principal: " . $e->getMessage(), [
                'datos_bancarios_id' => $id,
                'usuario_id' => $usuarioId,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al establecer cuenta principal: ' . $e->getMessage());
        }
    }
    
    /**
     * Eliminar datos bancarios
     */
    public function eliminarDatosBancarios($id)
    {
        $usuarioId = Auth::id();
        
        try {
            $datosBancarios = DatosBancario::where('usuario_id', $usuarioId)
                                         ->findOrFail($id);
            
            // Verificar si hay pagos asociados a estos datos bancarios
            $pagosAsociados = Pago::where('datos_bancarios_id', $id)->exists();
            
            if ($pagosAsociados) {
                return redirect()->back()
                               ->with('error', 'No se puede eliminar esta cuenta porque hay pagos asociados a ella.');
            }
            
            $datosBancarios->delete();
            
            return redirect()->route('consultor.datos-bancarios.index')
                           ->with('success', 'Datos bancarios eliminados correctamente.');
            
        } catch (\Exception $e) {
            Log::error("Error al eliminar datos bancarios: " . $e->getMessage(), [
                'datos_bancarios_id' => $id,
                'usuario_id' => $usuarioId,
                'exception' => $e
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al eliminar datos bancarios: ' . $e->getMessage());
        }
    }
}
