<?php

namespace App\Http\Controllers\Consultor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        // Obtener las empresas asignadas al consultor autenticado
        $empresas = Auth::user()->empresas()->get();
        return view('consultor.empresas', compact('empresas'));
    }
}
