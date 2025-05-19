<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'nombre', 'dni', 'descripcion',
        'telefono', 'fecha_inicio', 'fecha_fin', 'estado'
    ];
}
