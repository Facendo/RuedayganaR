<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::view("/terminos_y_condiciones", "terminos") -> name("terminos");
Route::view("/politicas_de_privacidad", "politica_de_privacidad") -> name("politica");


require __DIR__.'/auth.php';
require __DIR__.'/cliente.php';
require __DIR__.'/admin.php';
require __DIR__.'/sorteo.php';
require __DIR__.'/premio.php';
require __DIR__.'/ticket.php';

