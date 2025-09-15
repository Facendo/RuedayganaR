<?php

use App\Http\Controllers\PagoController;
use App\Http\Controllers\SorteoController;
use App\Http\Controllers\TicketController;
use App\Models\Pago;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [PagoController::class, 'index'])->name('pago.index')->middleware('auth');

Route::delete('/admin/{id_pago}', [PagoController::class, 'destroy'])->name('pago.destroy')->middleware('auth');
Route::get('/tickets/show', [TicketController::class, 'show'])->name('admin.showticket')->middleware('auth');
Route::get('/ticket/show',[TicketController::class, 'showticket'])->name('admin.ticket')->middleware('auth');
Route::post('/showcomprobante', [PagoController::class, 'showComprobante'])->name('admin.showcomprobante')->middleware('auth');
Route::put('/admin/pago/update',[PagoController::class, 'update'])->name('pago.update')->middleware('auth');