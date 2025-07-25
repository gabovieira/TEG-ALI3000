<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConsultorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verificar que el usuario sea consultor
        $tipoUsuario = auth()->user()->tipo_usuario;
        
        if ($tipoUsuario !== 'consultor') {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}