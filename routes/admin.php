<?php

use App\Http\Controllers\PagoController;
use App\Http\Controllers\SorteoController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\RuletaController;
use App\Http\Controllers\RanuraController;
use App\Models\Pago;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [PagoController::class, 'index'])->name('pago.index')->middleware('auth');

Route::delete('/admin/{id_pago}', [PagoController::class, 'destroy'])->name('pago.destroy')->middleware('auth');
Route::get('/tickets/show', [TicketController::class, 'show'])->name('admin.showticket')->middleware('auth');
Route::get('/ticket/show',[TicketController::class, 'showticket'])->name('admin.ticket')->middleware('auth');
Route::post('/showcomprobante', [PagoController::class, 'showComprobante'])->name('admin.showcomprobante')->middleware('auth');
Route::put('/admin/pago/update',[PagoController::class, 'update'])->name('pago.update')->middleware('auth');
Route::get('/ruletas/creacion/{id_sorteo}', [RuletaController::class,'create'])->name('ruletas.creacion');
Route::get('/ranuras/creacion/{id_ruleta}', [RanuraController::class,'create'])->name('ranuras.creacion');
Route::post('/ruletas/store', [RuletaController::class,'store'])->name('ruletas.store');
Route::post('/ranuras/store', [RanuraController::class,'store'])->name('ranuras.store');
Route::get('/ruletas/editar/{id_ruleta}', [RuletaController::class,'edit'])->name('ruletas.editar');
Route::put('/ruletas/update', [RuletaController::class,'update'])->name('ruletas.update');
Route::put('/ranuras/update', [RanuraController::class,'update'])->name('ranuras.update');
Route::post('/ruleta/RuletClient',[RuletaController::class,'BuildRulet'])->name('ruleta.searchclient');
Route::delete('/ruleta/destroy',[RuletaController::class,'destroy'])->name('ruleta.destroy');
//Route for spinning the Rulet
Route::post('/ruleta/spin', [RuletaController::class,'Spin'])->name('ruleta.spin');
Route::put('/ruleta/ChangeState/{id_ruleta}',[RuletaController::class,'ActivarRuleta'])->name('ruleta.cambio_estado');
Route::post('/ruleta/sendmail',[RuletaController::class,'handleMailRequest'])->name('ruleta.sendmail');

