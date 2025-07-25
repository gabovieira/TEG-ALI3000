<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TokenRegistro;
use App\Models\Usuario;
use App\Models\DocumentoIdentidad;
use App\Models\DatosLaborales;
use App\Models\ContactoUsuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class TokenRegistroController extends Controller
{
    /**
     * Mostrar lista de tokens de registro
     */
    public function index()
    {
        // Debug: Verificar que el controlador se está ejecutando
        \Log::info('TokenRegistroController@index ejecutándose');
        
        $tokens = TokenRegistro::with('usuario')
                              ->orderBy('fecha_creacion', 'desc')
                              ->paginate(15);

        $estadisticas = [
            'total' => TokenRegistro::count(),
            'validos' => TokenRegistro::validos()->count(),
            'expirados' => TokenRegistro::expirados()->count(),
            'usados' => TokenRegistro::usados()->count(),
        ];

        return view('admin.tokens.index', compact('tokens', 'estadisticas'));
    }

    /**
     * Mostrar formulario para crear nuevo token
     */
    public function create()
    {
        return view('admin.tokens.create');
    }

    /**
     * Crear nuevo token de registro
     */
    public function store(Request $request)
    {
        \Log::info('TokenRegistroController@store iniciado', $request->all());
        
        $request->validate([
            'primer_nombre' => 'required|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'cedula_tipo' => 'required|in:V,E',
            'cedula' => 'required|numeric|digits_between:6,8',
            'rif_tipo' => 'nullable|in:V,E,J,P,G',
            'rif' => 'nullable|string|max:15',
            'rif_dv' => 'nullable|string|max:1',
            'tarifa_por_hora' => 'required|numeric|min:0',
            'nivel_desarrollo' => 'required|in:junior,semi-senior,senior',
            'email_notificacion' => 'nullable|email',
            'dias_expiracion' => 'required|integer|min:1|max:30',
            'enviar_email' => 'boolean',
        ]);
        
        \Log::info('Validación pasada correctamente');

        try {
            DB::beginTransaction();

            // Verificar que la cédula no exista
            $cedulaExiste = DocumentoIdentidad::where('tipo_documento', 'cedula')
                                             ->where('numero', $request->cedula_tipo . $request->cedula)
                                             ->exists();
            if ($cedulaExiste) {
                throw new \Exception('Ya existe un usuario con esta cédula');
            }

            // Verificar RIF si se proporciona
            if ($request->rif) {
                $rifCompleto = $request->rif_tipo . $request->rif;
                $rifExiste = DocumentoIdentidad::where('tipo_documento', 'rif')
                                              ->where('numero', $rifCompleto)
                                              ->exists();
                if ($rifExiste) {
                    throw new \Exception('Ya existe un usuario con este RIF');
                }
            }

            // Crear usuario pendiente (sin email aún)
            $usuario = Usuario::create([
                'tipo_usuario' => 'consultor',
                'primer_nombre' => $request->primer_nombre,
                'primer_apellido' => $request->primer_apellido,
                'email' => 'temp_' . time() . '@temp.com', // Email temporal
                'estado' => 'pendiente_registro',
                'creado_por' => auth()->id(),
            ]);

            // Crear documento de cédula
            DocumentoIdentidad::create([
                'usuario_id' => $usuario->id,
                'tipo_documento' => 'cedula',
                'numero' => $request->cedula_tipo . $request->cedula,
                'es_principal' => true,
            ]);

            // Crear documento RIF si se proporciona
            if ($request->rif) {
                DocumentoIdentidad::create([
                    'usuario_id' => $usuario->id,
                    'tipo_documento' => 'rif',
                    'numero' => $request->rif_tipo . $request->rif,
                    'digito_verificador' => $request->rif_dv,
                    'es_principal' => true,
                ]);
            }

            // Crear datos laborales
            DatosLaborales::create([
                'usuario_id' => $usuario->id,
                'tarifa_por_hora' => $request->tarifa_por_hora,
                'nivel_desarrollo' => $request->nivel_desarrollo,
            ]);

            // Crear contacto de email de notificación si se proporciona
            if ($request->email_notificacion) {
                ContactoUsuario::create([
                    'usuario_id' => $usuario->id,
                    'tipo_contacto' => 'email',
                    'valor' => $request->email_notificacion,
                    'es_principal' => true,
                ]);
            }

            // Generar token
            $token = TokenRegistro::generarToken($usuario->id, $request->dias_expiracion);

            // Enviar email si se solicita y hay email de notificación
            if ($request->enviar_email && $request->email_notificacion) {
                $this->enviarEmailRegistro($usuario, $token, $request->email_notificacion);
            }

            DB::commit();

            return redirect()->route('admin.tokens.index')
                           ->with('success', 'Consultor pre-registrado y token creado exitosamente')
                           ->with('token_url', $token->url_registro);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error en TokenRegistroController@store: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creando registro: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un token
     */
    public function show(TokenRegistro $token)
    {
        $token->load('usuario');
        return view('admin.tokens.show', compact('token'));
    }

    /**
     * Reenviar token por email
     */
    public function reenviar(TokenRegistro $token)
    {
        if (!$token->isValido()) {
            return redirect()->back()->with('error', 'No se puede reenviar un token expirado o usado');
        }

        try {
            $this->enviarEmailRegistro($token->usuario, $token);
            
            return redirect()->back()->with('success', 'Token reenviado por email exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error enviando email: ' . $e->getMessage());
        }
    }

    /**
     * Extender expiración de token
     */
    public function extender(Request $request, TokenRegistro $token)
    {
        $request->validate([
            'dias' => 'required|integer|min:1|max:30',
        ]);

        if ($token->isUsado()) {
            return redirect()->back()->with('error', 'No se puede extender un token ya usado');
        }

        try {
            $token->extenderExpiracion($request->dias);
            
            return redirect()->back()->with('success', 
                "Token extendido por {$request->dias} días. Nueva fecha de expiración: " . 
                $token->fecha_expiracion->format('d/m/Y H:i')
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error extendiendo token: ' . $e->getMessage());
        }
    }

    /**
     * Invalidar token
     */
    public function invalidar(TokenRegistro $token)
    {
        if ($token->isUsado()) {
            return redirect()->back()->with('error', 'El token ya está usado');
        }

        try {
            $token->marcarComoUsado();
            
            return redirect()->back()->with('success', 'Token invalidado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error invalidando token: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar token y usuario asociado
     */
    public function destroy(TokenRegistro $token)
    {
        try {
            $usuario = $token->usuario;
            
            // Solo eliminar si el usuario está pendiente de registro
            if ($usuario && $usuario->estado === 'pendiente_registro') {
                $usuario->delete();
            }
            
            $token->delete();
            
            return redirect()->back()->with('success', 'Token eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error eliminando token: ' . $e->getMessage());
        }
    }

    /**
     * Limpiar tokens expirados
     */
    public function limpiarExpirados()
    {
        try {
            $eliminados = TokenRegistro::limpiarTokensExpirados();
            
            return redirect()->back()->with('success', 
                "Se eliminaron {$eliminados} tokens expirados"
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error limpiando tokens: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas para API
     */
    public function estadisticas()
    {
        return response()->json([
            'total' => TokenRegistro::count(),
            'validos' => TokenRegistro::validos()->count(),
            'expirados' => TokenRegistro::expirados()->count(),
            'usados' => TokenRegistro::usados()->count(),
            'proximos_a_expirar' => TokenRegistro::obtenerTokensProximosAExpirar(3)->count(),
        ]);
    }

    /**
     * Enviar email de registro
     */
    private function enviarEmailRegistro($usuario, $token, $emailDestino = null)
    {
        $email = $emailDestino ?? $usuario->email;
        
        // Aquí implementarías el envío de email
        // Por ahora solo simularemos que se envía
        
        // Mail::send('emails.token-registro', [
        //     'usuario' => $usuario,
        //     'token' => $token,
        //     'url' => $token->url_registro
        // ], function ($message) use ($email) {
        //     $message->to($email)
        //             ->subject('Invitación para completar registro - ALI3000');
        // });
        
        // Por ahora solo logueamos
        \Log::info("Email de registro enviado a {$email} con token {$token->token}");
    }
}