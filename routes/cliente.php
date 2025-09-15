<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/compra/{id_sorteo}',[ClienteController::class, 'index'])->name('compra');

Route::post('/compra',[ClienteController::class, 'store'])->name('cliente.store');