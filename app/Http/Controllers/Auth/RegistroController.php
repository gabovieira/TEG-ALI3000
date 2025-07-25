<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TokenRegistro;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    /**
     * Mostrar vista para ingresar token
     */
    public function mostrarIngresarToken()
    {
        return view('auth.ingresar-token');
    }

    /**
     * Validar token ingresado y redirigir al formulario
     */
    public function validarToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        // Validar que el token existe y es válido
        $validacion = TokenRegistro::validarToken($request->token);
        
        if (!$validacion['valido']) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', $validacion['mensaje']);
        }

        // Redirigir al formulario de registro con el token
        return redirect()->route('registro.formulario', ['token' => $request->token]);
    }

    /**
     * Mostrar formulario de registro con token
     */
    public function mostrarFormulario($token)
    {
        try {
            // Validar token
            $validacion = TokenRegistro::validarToken($token);
            
            if (!$validacion['valido']) {
                \Log::warning('Token inválido al mostrar formulario', [
                    'token' => $token,
                    'mensaje' => $validacion['mensaje']
                ]);
                
                return view('auth.registro-error', [
                    'mensaje' => $validacion['mensaje']
                ]);
            }

            $tokenRegistro = $validacion['token'];
            $usuario = $tokenRegistro->usuario;

            if (!$usuario) {
                \Log::warning('Usuario no encontrado al mostrar formulario', [
                    'token' => $token,
                    'usuario_id' => $tokenRegistro->usuario_id
                ]);
                
                return view('auth.registro-error', [
                    'mensaje' => 'No se encontró el usuario asociado a este token'
                ]);
            }

            // Registrar en el log para depuración
            \Log::info('Mostrando formulario de registro', [
                'token' => $token,
                'usuario_id' => $usuario->id,
                'usuario_nombre' => $usuario->primer_nombre . ' ' . $usuario->primer_apellido,
                'usuario_email' => $usuario->email
            ]);

            // Usar la vista principal de registro
            return view('auth.registro', [
                'token' => $token,
                'usuario' => $usuario,
                'tokenRegistro' => $tokenRegistro
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al mostrar formulario de registro', [
                'token' => $token,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('auth.registro-error', [
                'mensaje' => 'Error al cargar el formulario de registro: ' . $e->getMessage(),
                'detalle' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Procesar registro del usuario
     */
    public function procesarRegistro(Request $request, $token)
    {
        // Registrar en el log para depuración
        \Log::info('Procesando registro', [
            'token' => $token,
            'request' => $request->except(['password', 'password_confirmation'])
        ]);
        
        // Validar token nuevamente
        $validacion = TokenRegistro::validarToken($token);
        
        if (!$validacion['valido']) {
            \Log::error('Token inválido al procesar registro', [
                'token' => $token,
                'mensaje' => $validacion['mensaje']
            ]);
            
            return redirect()->route('registro.error')
                           ->with('error', $validacion['mensaje']);
        }

        $tokenRegistro = $validacion['token'];
        $usuario = $tokenRegistro->usuario;
        
        if (!$usuario) {
            \Log::error('Usuario no encontrado al procesar registro', [
                'token' => $token,
                'usuario_id' => $tokenRegistro->usuario_id
            ]);
            
            return redirect()->route('registro.error')
                           ->with('error', 'No se encontró el usuario asociado a este token');
        }

        // Validar datos del formulario simplificado para pruebas
        $validatedData = $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'terminos' => 'required|accepted',
        ]);

        try {
            \Log::info('Actualizando usuario', [
                'usuario_id' => $usuario->id,
                'telefono' => $request->filled('telefono') ? 'proporcionado' : 'no proporcionado'
            ]);
            
            // Actualizar usuario con datos simplificados (sin cambiar el email)
            $usuario->password_hash = Hash::make($request->password);
            $usuario->estado = 'activo';
            $usuario->save();

            // Crear datos laborales si se proporcionó teléfono
            if ($request->filled('telefono')) {
                // Verificar si ya existe un registro de datos laborales
                $datosLaborales = $usuario->datosLaborales;
                
                if ($datosLaborales) {
                    // Actualizar el registro existente
                    $datosLaborales->telefono_personal = $request->telefono;
                    $datosLaborales->save();
                    
                    \Log::info('Datos laborales actualizados', [
                        'usuario_id' => $usuario->id,
                        'datos_laborales_id' => $datosLaborales->id
                    ]);
                } else {
                    // Crear un nuevo registro
                    $datosLaborales = $usuario->datosLaborales()->create([
                        'telefono_personal' => $request->telefono,
                        'tarifa_por_hora' => 0, // Valor por defecto
                        'nivel_desarrollo' => 'junior', // Valor por defecto
                    ]);
                    
                    \Log::info('Datos laborales creados', [
                        'usuario_id' => $usuario->id,
                        'datos_laborales_id' => $datosLaborales->id
                    ]);
                }
            }

            // Marcar token como usado
            $tokenRegistro->marcarComoUsado();
            
            \Log::info('Token marcado como usado', [
                'token_id' => $tokenRegistro->id
            ]);

            // Autenticar usuario automáticamente
            Auth::login($usuario);
            
            \Log::info('Usuario autenticado correctamente', [
                'usuario_id' => $usuario->id
            ]);

            // Redirigir al dashboard del consultor
            return redirect()->route('consultor.dashboard')
                           ->with('success', '¡Registro completado exitosamente! Bienvenido a ALI3000.');

        } catch (\Exception $e) {
            \Log::error('Error al procesar registro', [
                'token' => $token,
                'usuario_id' => $usuario->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Mostrar información detallada del error para depuración
            return view('auth.registro-error', [
                'mensaje' => 'Error completando el registro: ' . $e->getMessage(),
                'detalle' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Mostrar página de error de token
     */
    public function mostrarError()
    {
        return view('auth.registro-error', [
            'mensaje' => 'Token inválido o expirado'
        ]);
    }
}