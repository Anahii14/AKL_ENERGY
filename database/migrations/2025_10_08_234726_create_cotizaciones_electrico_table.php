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
        Schema::create('cotizaciones_electrico', function (Blueprint $table) {
            $table->id();

            // Relación: usuario que crea la solicitud
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Campos del formulario
            $table->string('tipo_servicio');                  // * requerido
            $table->decimal('area_m2', 10, 2);                // * requerido
            $table->text('descripcion_trabajo');              // * requerido
            $table->string('voltaje_requerido')->nullable();  // select
            $table->unsignedInteger('duracion_dias')->nullable();
            $table->string('ubicacion_obra');                 // * requerido
            $table->date('fecha_inicio');                     // * requerido
            $table->boolean('incluir_materiales')->default(true);
            $table->text('observaciones')->nullable();

            // Flujo
            $table->string('estado')->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_electrico');
    }
};
