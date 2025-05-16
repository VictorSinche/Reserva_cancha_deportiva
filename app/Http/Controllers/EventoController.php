<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        return response()->json([
            [
                'title' => 'Clase de Prueba',
                'start' => '2024-01-02T09:00:00',
                'end' => '2024-01-02T11:00:00'
            ]
        ]);
    }

    public function mostrarCalendario()
    {
        return view('calendario');
    }

}
