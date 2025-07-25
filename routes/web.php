<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Landing page (página principal)
Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/landing', function () {
    return view('landing');
})->name('landing');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/registro', [App\Http\Controllers\Auth\RegistroController::class, 'mostrarIngresarToken'])->name('registro.token');
Route::post('/registro/validar-token', [App\Http\Controllers\Auth\RegistroController::class, 'validarToken'])->name('registro.validar-token');
Route::get('/registro/{token}', [App\Http\Controllers\Auth\RegistroController::class, 'mostrarFormulario'])->name('registro.formulario');
Route::post('/registro/{token}', [App\Http\Controllers\Auth\RegistroController::class, 'procesarRegistro'])->name('registro.procesar');
Route::get('/registro-error', [App\Http\Controllers\Auth\RegistroController::class, 'mostrarError'])->name('registro.error');

// Rutas de prueba eliminadas

// Admin Routes (protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/actualizar-tasa-bcv', [App\Http\Controllers\AdminController::class, 'actualizarTasaBcv'])->name('actualizar-tasa-bcv');
    Route::post('/actualizar-tasa-api', [App\Http\Controllers\AdminController::class, 'actualizarTasaApi'])->name('actualizar-tasa-api');
    
    // Gestión de usuarios
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [App\Http\Controllers\UsuariosController::class, 'index'])->name('index');
        Route::get('/crear', [App\Http\Controllers\UsuariosController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\UsuariosController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\UsuariosController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [App\Http\Controllers\UsuariosController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\UsuariosController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\UsuariosController::class, 'destroy'])->name('destroy');
        
        // Rutas para asignación de empresas
        Route::get('/{id}/asignar-empresas', [App\Http\Controllers\UsuariosController::class, 'asignarEmpresas'])->name('asignar-empresas');
        Route::post('/{id}/asignar-empresas', [App\Http\Controllers\UsuariosController::class, 'guardarAsignaciones'])->name('guardar-asignaciones');
        Route::delete('/{usuarioId}/empresas/{empresaId}', [App\Http\Controllers\UsuariosController::class, 'eliminarAsignacion'])->name('eliminar-asignacion');
    });


    
    // Gestión de empresas
    Route::prefix('empresas')->name('empresas.')->group(function () {
        Route::get('/', [App\Http\Controllers\EmpresasController::class, 'index'])->name('index');
        Route::get('/crear', [App\Http\Controllers\EmpresasController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\EmpresasController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\EmpresasController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [App\Http\Controllers\EmpresasController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\EmpresasController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\EmpresasController::class, 'destroy'])->name('destroy');
        
        // Rutas para asignación de consultores
        Route::get('/{id}/asignar-consultores', [App\Http\Controllers\EmpresasController::class, 'asignarConsultores'])->name('asignar-consultores');
        Route::post('/{id}/asignar-consultores', [App\Http\Controllers\EmpresasController::class, 'guardarAsignaciones'])->name('guardar-asignaciones');
        Route::delete('/{empresaId}/consultores/{usuarioId}', [App\Http\Controllers\EmpresasController::class, 'eliminarAsignacion'])->name('eliminar-asignacion');
    });
    
    // Horas routes
    Route::prefix('horas')->name('horas.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\HorasController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\HorasController::class, 'show'])->name('show');
        Route::post('/{id}/aprobar', [App\Http\Controllers\Admin\HorasController::class, 'aprobar'])->name('aprobar');
        Route::post('/{id}/rechazar', [App\Http\Controllers\Admin\HorasController::class, 'rechazar'])->name('rechazar');
        Route::post('/aprobar-multiple', [App\Http\Controllers\Admin\HorasController::class, 'aprobarMultiple'])->name('aprobar-multiple');
        Route::post('/rechazar-multiple', [App\Http\Controllers\Admin\HorasController::class, 'rechazarMultiple'])->name('rechazar-multiple');
    });
    
    // Pagos routes
    Route::prefix('pagos')->name('pagos.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PagosController::class, 'index'])->name('index');
        Route::get('/generar', [App\Http\Controllers\Admin\PagosController::class, 'mostrarGenerarPagos'])->name('generar.form');
        Route::post('/generar', [App\Http\Controllers\Admin\PagosController::class, 'generarPagos'])->name('generar');
        Route::get('/calcular', [App\Http\Controllers\Admin\PagosController::class, 'calcular'])->name('calcular');
        Route::get('/preview', [App\Http\Controllers\Admin\PagosController::class, 'preview'])->name('preview');
        Route::get('/calcular-individual', [App\Http\Controllers\Admin\PagosController::class, 'calcularPagoIndividual'])->name('calcular.individual');
        Route::get('/{id}', [App\Http\Controllers\Admin\PagosController::class, 'show'])->name('show');
        Route::post('/{id}/procesar', [App\Http\Controllers\Admin\PagosController::class, 'procesarPago'])->name('procesar');
        Route::post('/{id}/pagar', [App\Http\Controllers\Admin\PagosController::class, 'marcarPagado'])->name('pagar');
        Route::post('/{id}/anular', [App\Http\Controllers\Admin\PagosController::class, 'anular'])->name('anular');
        Route::get('/{id}/comprobante', [App\Http\Controllers\Admin\PagosController::class, 'descargarComprobante'])->name('comprobante');
    });
    
    // Datos bancarios routes
    Route::prefix('datos-bancarios')->name('datos-bancarios.')->group(function () {
        Route::get('/consultor/{consultorId}', [App\Http\Controllers\Admin\DatosBancariosController::class, 'index'])->name('index');
        Route::get('/consultor/{consultorId}/crear', [App\Http\Controllers\Admin\DatosBancariosController::class, 'create'])->name('create');
        Route::post('/consultor/{consultorId}', [App\Http\Controllers\Admin\DatosBancariosController::class, 'store'])->name('store');
        Route::get('/consultor/{consultorId}/{id}/editar', [App\Http\Controllers\Admin\DatosBancariosController::class, 'edit'])->name('edit');
        Route::put('/consultor/{consultorId}/{id}', [App\Http\Controllers\Admin\DatosBancariosController::class, 'update'])->name('update');
        Route::post('/consultor/{consultorId}/{id}/principal', [App\Http\Controllers\Admin\DatosBancariosController::class, 'establecerPrincipal'])->name('principal');
        Route::delete('/consultor/{consultorId}/{id}', [App\Http\Controllers\Admin\DatosBancariosController::class, 'destroy'])->name('destroy');
        Route::post('/validar-numero-cuenta', [App\Http\Controllers\Admin\DatosBancariosController::class, 'validarNumeroCuenta'])->name('validar-numero-cuenta');
    });
    
    // Datos bancarios routes
    Route::prefix('datos-bancarios')->name('datos-bancarios.')->group(function () {
        Route::get('/{consultorId}', [App\Http\Controllers\Admin\DatosBancariosController::class, 'index'])->name('index');
        Route::get('/{consultorId}/create', [App\Http\Controllers\Admin\DatosBancariosController::class, 'create'])->name('create');
        Route::post('/{consultorId}', [App\Http\Controllers\Admin\DatosBancariosController::class, 'store'])->name('store');
        Route::get('/{consultorId}/{id}/edit', [App\Http\Controllers\Admin\DatosBancariosController::class, 'edit'])->name('edit');
        Route::put('/{consultorId}/{id}', [App\Http\Controllers\Admin\DatosBancariosController::class, 'update'])->name('update');
        Route::post('/{consultorId}/{id}/principal', [App\Http\Controllers\Admin\DatosBancariosController::class, 'establecerPrincipal'])->name('principal');
        Route::delete('/{consultorId}/{id}', [App\Http\Controllers\Admin\DatosBancariosController::class, 'destroy'])->name('destroy');
        Route::post('/validar', [App\Http\Controllers\Admin\DatosBancariosController::class, 'validarNumeroCuenta'])->name('validar');
    });
    
    // Tokens de registro routes
    Route::prefix('tokens')->name('tokens.')->group(function () {
        Route::get('/', [App\Http\Controllers\TokenRegistroController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\TokenRegistroController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\TokenRegistroController::class, 'store'])->name('store');
        Route::get('/{token}', [App\Http\Controllers\TokenRegistroController::class, 'show'])->name('show');
        Route::post('/{token}/reenviar', [App\Http\Controllers\TokenRegistroController::class, 'reenviar'])->name('reenviar');
        Route::patch('/{token}/extender', [App\Http\Controllers\TokenRegistroController::class, 'extender'])->name('extender');
        Route::patch('/{token}/invalidar', [App\Http\Controllers\TokenRegistroController::class, 'invalidar'])->name('invalidar');
        Route::delete('/{token}', [App\Http\Controllers\TokenRegistroController::class, 'destroy'])->name('destroy');
        Route::delete('/limpiar-expirados', [App\Http\Controllers\TokenRegistroController::class, 'limpiarExpirados'])->name('limpiar-expirados');
        Route::get('/api/estadisticas', [App\Http\Controllers\TokenRegistroController::class, 'estadisticas'])->name('estadisticas');
    });
    
    // Configuración del sistema routes
    Route::prefix('configuracion')->name('configuracion.')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('index');
        Route::get('/{id}/editar', [App\Http\Controllers\ConfiguracionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\ConfiguracionController::class, 'update'])->name('update');
        Route::post('/update-categoria', [App\Http\Controllers\ConfiguracionController::class, 'updateCategoria'])->name('update-categoria');
        Route::post('/reset', [App\Http\Controllers\ConfiguracionController::class, 'reset'])->name('reset');
        Route::get('/api', [App\Http\Controllers\ConfiguracionController::class, 'api'])->name('api');
    });
});

// Consultor Routes (protected)
Route::middleware(['auth', 'consultor'])->prefix('consultor')->name('consultor.')->group(function () {
    Route::get('/dashboard', function () {
        // Check if user is consultor
        if (auth()->user()->tipo_usuario !== 'consultor') {
            return redirect()->route('admin.dashboard');
        }
        return view('consultor.dashboard');
    })->name('dashboard');
    
    // Horas routes
    Route::resource('horas', \App\Http\Controllers\Consultor\RegistroHorasController::class);
    
    // Pagos routes
    Route::prefix('pagos')->name('pagos.')->group(function () {
        Route::get('/', [App\Http\Controllers\Consultor\PagosController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Consultor\PagosController::class, 'show'])->name('show');
        Route::get('/{id}/confirmar', [App\Http\Controllers\Consultor\PagosController::class, 'mostrarConfirmar'])->name('confirmar.form');
        Route::post('/{id}/confirmar', [App\Http\Controllers\Consultor\PagosController::class, 'confirmar'])->name('confirmar');
        Route::post('/{id}/rechazar', [App\Http\Controllers\Consultor\PagosController::class, 'rechazar'])->name('rechazar');
        Route::get('/{id}/comprobante', [App\Http\Controllers\Consultor\PagosController::class, 'descargarComprobante'])->name('comprobante');
        
        // Datos bancarios dentro de pagos
        Route::get('/datos-bancarios', [App\Http\Controllers\Consultor\PagosController::class, 'datosBancarios'])->name('datos-bancarios');
    });
    
    // Datos bancarios routes
    Route::prefix('datos-bancarios')->name('datos-bancarios.')->group(function () {
        Route::get('/', [App\Http\Controllers\Consultor\DatosBancariosController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Consultor\DatosBancariosController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Consultor\DatosBancariosController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\Consultor\DatosBancariosController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Consultor\DatosBancariosController::class, 'update'])->name('update');
        Route::post('/{id}/principal', [App\Http\Controllers\Consultor\DatosBancariosController::class, 'establecerPrincipal'])->name('principal');
        Route::delete('/{id}', [App\Http\Controllers\Consultor\DatosBancariosController::class, 'destroy'])->name('destroy');
    });
});
