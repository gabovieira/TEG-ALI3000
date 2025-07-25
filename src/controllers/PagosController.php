<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\PagoService;
use App\Services\TarifaService;
use App\Services\ConfiguracionFiscalService;
use App\Services\TasaBcvService;
use App\Models\Pago;
use App\Models\PagoDetalle;
use App\Models\User;
use App\Models\Empresa;

class PagosController extends Controller
{
    public function index(Request $request)
    {
        $consultores = User::where('tipo_usuario', 'consultor')->get();
        $empresas = Empresa::all();
        $pagos = Pago::with(['consultor', 'empresa'])->orderByDesc('created_at')->paginate(15);
        return view('admin.pagos.index', compact('consultores', 'empresas', 'pagos'));
    }

    public function show($id)
    {
        $pago = Pago::with(['consultor', 'empresa', 'detalles', 'historial.usuario'])->findOrFail($id);
        return view('admin.pagos.show', compact('pago'));
    }

    public function generarPagos(Request $request)
    {
        $quincenas = PagoService::getPeriodosQuincenales();
        $empresas = Empresa::all();
        $tasa_bcv = TasaBcvService::getTasaActual();
        $pagos_preview = null;
        if ($request->isMethod('post')) {
            $pagos_preview = PagoService::previewPagosQuincena($request->quincena, $request->empresa);
            // Validaciones y lógica de generación real se harían aquí
        }
        return view('admin.pagos.generar', compact('quincenas', 'empresas', 'tasa_bcv', 'pagos_preview'));
    }

    public function marcarPagado($id, Request $request)
    {
        $pago = Pago::findOrFail($id);
        PagoService::marcarComoPagado($pago->id, now(), $request->observaciones);
        return redirect()->route('admin.pagos.show', $id)->with('success', 'Pago marcado como pagado.');
    }

    public function anular($id, Request $request)
    {
        $pago = Pago::findOrFail($id);
        PagoService::anularPago($pago->id, $request->motivo);
        return redirect()->route('admin.pagos.show', $id)->with('success', 'Pago anulado.');
    }
}
