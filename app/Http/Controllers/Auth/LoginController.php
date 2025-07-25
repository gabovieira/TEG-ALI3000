<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    /**
     * Handle the login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Find the user by email
        $usuario = Usuario::where('email', $request->email)->first();
        
        // Check if user exists and password is correct
        if (!$usuario || !Hash::check($request->password, $usuario->password_hash)) {
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas son incorrectas.',
            ])->withInput($request->only('email'));
        }
        
        // Check if user is active
        if ($usuario->estado !== 'activo') {
            return back()->withErrors([
                'email' => 'Tu cuenta no está activa. Contacta al administrador.',
            ])->withInput($request->only('email'));
        }
        
        // Login the user
        Auth::login($usuario, $request->boolean('remember'));
        
        // Regenerate session
        $request->session()->regenerate();
        
        // Redirect based on user type
        if ($usuario->tipo_usuario === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } else {
            return redirect()->intended(route('consultor.dashboard'));
        }
    }
    
    /**
     * Log the user out
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}