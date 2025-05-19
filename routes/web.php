<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/eventos', [ReservaController::class, 'index']);
Route::get('/', [ReservaController::class, 'mostrarCalendario']);
Route::post('/reservar', [ReservaController::class, 'store'])->name('reservar');

