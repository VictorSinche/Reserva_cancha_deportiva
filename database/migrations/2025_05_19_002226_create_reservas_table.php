<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
        {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('dni', 8);
            // $table->string('especialidad');
            $table->text('descripcion')->nullable();
            $table->string('telefono', 9);
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
            $table->string('color', 7)->nullable(); // #RRGGBB
            $table->enum('estado', ['reservado', 'cancelado', 'finalizado'])->default('reservado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
