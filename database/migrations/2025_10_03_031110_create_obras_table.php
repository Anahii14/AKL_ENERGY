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
        Schema::create('obras', function (Blueprint $table) {
            $table->id();

            // Campos del modal
            $table->string('nombre', 180);             // Nombre de la obra
            $table->string('codigo', 50)->unique();    // Código interno/legible
            $table->string('direccion', 255)->nullable();

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin_estimada')->nullable();

            // Recomendado para listados y filtros
            $table->enum('estado', ['activa','inactiva'])
                  ->default('activa')
                  ->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras');
    }
};
