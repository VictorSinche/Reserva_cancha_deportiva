<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use Illuminate\Support\Facades\Log;

class ReservaController extends Controller
{

public function index(Request $request)
    {
        Log::info('🧪 Consulta a eventos', $request->query());

        $eventos = Reserva::all()->map(function ($reserva) {
            return [
                'title' => $reserva->nombre,
                'start' => $reserva->fecha_inicio,
                'end' => $reserva->fecha_fin,
                'color' => $reserva->color ?? '#E40D5E',
            ];

        });

        return response()->json($eventos);
    }

public function mostrarCalendario()
    {
        return view('calendario');
    }

public function store(Request $request)
{
    Log::info('Solicitud de reserva recibida', $request->all());

    try {
        $validated = $request->validate([
            'nombre' => 'required',
            'dni' => 'required|digits:8',
            // 'especialidad' => 'required',
            'telefono' => 'required|digits:9',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $validated['fecha_inicio'] = \Carbon\Carbon::parse($validated['fecha_inicio'])->toDateTimeString();
        $validated['fecha_fin'] = \Carbon\Carbon::parse($validated['fecha_fin'])->toDateTimeString();
        $validated['estado'] = 'reservado';
        $validated['color'] = '#' . substr(md5(now()->timestamp . rand()), 0, 6);

        Reserva::create($validated);

        Log::info('Reserva creada exitosamente');

        // ✅ Si viene desde fetch/AJAX (solicitud con JSON)
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        // ✅ Si es un form tradicional
        return redirect('/')->with('success', 'Reserva creada exitosamente');
    }

    // ⚠️ Captura errores de validación si no son manejados automáticamente
    catch (\Illuminate\Validation\ValidationException $e) {
        if ($request->expectsJson()) {
            return response()->json([
                'errors' => $e->validator->errors()
            ], 422);
        }
        return back()->withErrors($e->validator)->withInput();
    }

    // ⚠️ Captura otros errores inesperados
    catch (\Exception $e) {
        Log::error('Error al guardar reserva', ['message' => $e->getMessage()]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Error interno al guardar la reserva'
            ], 500);
        }

        return back()->with('error', 'Error al guardar la reserva.');
    }
}


}
