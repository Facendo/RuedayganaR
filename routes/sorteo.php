
<?php
use App\Http\Controllers\PagoController;
use App\Http\Controllers\SorteoController;
use App\Models\Pago;
use Illuminate\Support\Facades\Route;

Route::get('/', [SorteoController::class, 'index'])->name('sorteo.index');
Route::get('/admin/sorteos/create', [SorteoController::class, 'create'])->name('sorteo.create')->middleware('auth');
Route::post('/sorteos/store', [SorteoController::class, 'store'])->name('sorteo.store')->middleware('auth');
Route::get('/admin/sorteos/{sorteo}/edit', [SorteoController::class, 'edit'])->name('sorteo.edit')->middleware('auth');
Route::put('/admin/sorteo/update', [SorteoController::class, 'update'])->name('sorteo.actualizar')->middleware('auth');
Route::delete('/admin/sorteos/{sorteo}', [SorteoController::class, 'destroy'])->name('sorteo.destroy')->middleware('auth');
Route::put('/sorteos/cambio_estado/{id}', [SorteoController::class, 'cambio_de_estado'])->name('sorteo.cambio_estado')->middleware('auth');