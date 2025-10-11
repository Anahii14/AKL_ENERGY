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
        Schema::create('cotizaciones_gruas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // cliente que crea la cotización
            $table->string('tipo_grua');
            $table->integer('capacidad'); // en toneladas
            $table->integer('dias_alquiler');
            $table->date('fecha_inicio');
            $table->string('ubicacion_obra');
            
            // Servicios adicionales (booleanos)
            $table->boolean('incluye_operador')->default(false);
            $table->boolean('incluye_rigger')->default(false);
            $table->boolean('incluye_combustible')->default(false);

            $table->text('observaciones')->nullable();
            $table->decimal('precio_total', 12, 2)->nullable();
            $table->enum('estado', ['pendiente','enviada','aceptada','rechazada'])
                  ->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones_gruas');
    }
};
