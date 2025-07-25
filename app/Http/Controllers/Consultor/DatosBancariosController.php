<?php

namespace App\Http\Controllers\Consultor;

use App\Http\Controllers\Controller;
use App\Models\DatosBancario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DatosBancariosController extends Controller
{
    /**
     * Mostrar los datos bancarios del consultor
     */
    public function index()
    {
        $usuario = Auth::user();
        $datosBancarios = DatosBancario::where('usuario_id', $usuario->id)
            ->orderBy('es_principal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('consultor.datos-bancarios.index', compact('datosBancarios'));
    }

    /**
     * Mostrar formulario para crear nuevos datos bancarios
     */
    public function create()
    {
        $bancos = $this->getBancos();
        $tiposCuenta = $this->getTiposCuenta();
        
        return view('consultor.datos-bancarios.create', compact('bancos', 'tiposCuenta'));
    }

    /**
     * Guardar nuevos datos bancarios
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banco' => 'required|string|max:100',
            'tipo_cuenta' => 'required|string|in:ahorro,corriente',
            'numero_cuenta' => 'required|string|max:50',
            'cedula_rif' => 'required|string|max:20',
            'titular' => 'required|string|max:255',
            'observaciones' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $usuario = Auth::user();
        
        // Si es la primera cuenta, marcarla como principal
        $esPrimera = !DatosBancario::where('usuario_id', $usuario->id)->exists();

        $datosBancarios = DatosBancario::create([
            'usuario_id' => $usuario->id,
            'banco' => $request->banco,
            'tipo_cuenta' => $request->tipo_cuenta,
            'numero_cuenta' => $request->numero_cuenta,
            'cedula_rif' => $request->cedula_rif,
            'titular' => $request->titular,
            'es_principal' => $esPrimera,
            'estado' => DatosBancario::ESTADO_ACTIVO,
            'observaciones' => $request->observaciones
        ]);

        return redirect()->route('consultor.datos-bancarios.index')
            ->with('success', 'Datos bancarios agregados exitosamente.');
    }

    /**
     * Mostrar formulario para editar datos bancarios
     */
    public function edit($id)
    {
        $usuario = Auth::user();
        $datosBancarios = DatosBancario::where('usuario_id', $usuario->id)
            ->where('id', $id)
            ->firstOrFail();

        $bancos = $this->getBancos();
        $tiposCuenta = $this->getTiposCuenta();

        return view('consultor.datos-bancarios.edit', compact('datosBancarios', 'bancos', 'tiposCuenta'));
    }

    /**
     * Actualizar datos bancarios
     */
    public function update(Request $request, $id)
    {
        $usuario = Auth::user();
        $datosBancarios = DatosBancario::where('usuario_id', $usuario->id)
            ->where('id', $id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'banco' => 'required|string|max:100',
            'tipo_cuenta' => 'required|string|in:ahorro,corriente',
            'numero_cuenta' => 'required|string|max:50',
            'cedula_rif' => 'required|string|max:20',
            'titular' => 'required|string|max:255',
            'observaciones' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $datosBancarios->update([
            'banco' => $request->banco,
            'tipo_cuenta' => $request->tipo_cuenta,
            'numero_cuenta' => $request->numero_cuenta,
            'cedula_rif' => $request->cedula_rif,
            'titular' => $request->titular,
            'observaciones' => $request->observaciones
        ]);

        return redirect()->route('consultor.datos-bancarios.index')
            ->with('success', 'Datos bancarios actualizados exitosamente.');
    }

    /**
     * Establecer cuenta como principal
     */
    public function establecerPrincipal($id)
    {
        $usuario = Auth::user();
        $datosBancarios = DatosBancario::where('usuario_id', $usuario->id)
            ->where('id', $id)
            ->firstOrFail();

        $datosBancarios->establecerComoPrincipal();

        return redirect()->route('consultor.datos-bancarios.index')
            ->with('success', 'Cuenta establecida como principal exitosamente.');
    }

    /**
     * Eliminar datos bancarios
     */
    public function destroy($id)
    {
        $usuario = Auth::user();
        $datosBancarios = DatosBancario::where('usuario_id', $usuario->id)
            ->where('id', $id)
            ->firstOrFail();

        // No permitir eliminar si es la única cuenta
        $totalCuentas = DatosBancario::where('usuario_id', $usuario->id)->count();
        if ($totalCuentas <= 1) {
            return redirect()->route('consultor.datos-bancarios.index')
                ->with('error', 'No puedes eliminar tu única cuenta bancaria. Agrega otra cuenta antes de eliminar esta.');
        }

        // Si era la cuenta principal, establecer otra como principal
        if ($datosBancarios->es_principal) {
            $otraCuenta = DatosBancario::where('usuario_id', $usuario->id)
                ->where('id', '!=', $id)
                ->first();
            
            if ($otraCuenta) {
                $otraCuenta->establecerComoPrincipal();
            }
        }

        $datosBancarios->delete();

        return redirect()->route('consultor.datos-bancarios.index')
            ->with('success', 'Datos bancarios eliminados exitosamente.');
    }

    /**
     * Obtener lista de bancos
     */
    private function getBancos()
    {
        return [
            'Banco de Venezuela',
            'Banesco',
            'Mercantil',
            'Provincial',
            'Exterior',
            'Venezolano de Crédito',
            'Bicentenario',
            'Tesoro',
            'Bancaribe',
            'Activo',
            'Bancamiga',
            'Banplus',
            'Instituto Municipal de Crédito Popular',
            'Mi Banco',
            'Banco del Sur',
            'Banco Agrícola de Venezuela',
            'Banco Nacional de Crédito',
            'Banco Plaza',
            'Banco Sofitasa',
            'Banco Fondo Común',
            'Banco de la Gente Emprendedora',
            'Banco Caroní',
            'Bangente',
            'Banco Guayana',
            'Banco Industrial de Venezuela',
            'Banco Internacional de Desarrollo',
            'Banco Occidental de Descuento',
            'Banco Canarias de Venezuela',
            'Banco Confederado',
            'Banco del Pueblo Soberano',
            'Banco Espirito Santo',
            'Banco Tequendama',
            'Banco Único',
            'Banco Venezolano de Crédito',
            'Banco Zelle',
            'PayPal',
            'Binance',
            'AirTM',
            'Otro'
        ];
    }

    /**
     * Obtener tipos de cuenta
     */
    private function getTiposCuenta()
    {
        return [
            'ahorro' => 'Cuenta de Ahorro',
            'corriente' => 'Cuenta Corriente'
        ];
    }
}