<?php

use App\Http\Controllers\PremioController;
use Illuminate\Support\Facades\Route;

Route::post('/admin',[PremioController::class, 'store'])->name('premio.store')->middleware('auth');