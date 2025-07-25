
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagosController;

Route::prefix('admin/pagos')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [PagosController::class, 'index'])->name('admin.pagos.index');
    Route::get('/generar', [PagosController::class, 'generarPagos'])->name('admin.pagos.generar');
    Route::post('/generar', [PagosController::class, 'generarPagos']);
    Route::get('/{id}', [PagosController::class, 'show'])->name('admin.pagos.show');
    Route::get('/{id}/marcar-pagado', [PagosController::class, 'marcarPagado'])->name('admin.pagos.marcarPagado');
    Route::get('/{id}/anular', [PagosController::class, 'anular'])->name('admin.pagos.anular');
});
