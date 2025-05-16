<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/calendario', [EventoController::class, 'mostrarCalendario']);

