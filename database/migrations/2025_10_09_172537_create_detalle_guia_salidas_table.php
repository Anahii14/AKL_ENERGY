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
        Schema::create('detalle_guia_salidas', function (Blueprint $table) {
            $table->id();

            // Relación con la guía de salida (cabecera)
            $table->foreignId('guia_salida_id')
                ->constrained('guia_salidas')
                ->cascadeOnDelete();

            // Material asociado
            $table->foreignId('material_id')
                ->constrained('materiales');

            // Datos del material al momento de generar la guía (snapshot)
            $table->string('descripcion', 255);
            $table->string('unidad', 50)->nullable();
            $table->decimal('cantidad', 12, 2);

            // Stock antes y después de la salida (útil para auditoría)
            $table->decimal('stock_anterior', 12, 2)->nullable();
            $table->decimal('stock_nuevo', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_guia_salidas');
    }
};
