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
        Schema::create('guia_salidas', function (Blueprint $table) {
            $table->id();

            // Código único tipo G-29463353
            $table->string('codigo', 20)->unique()->index();

            // Pedido y Obra relacionados
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('obra_id')->constrained('obras')->cascadeOnUpdate()->restrictOnDelete();

            // Usuario que la genera (almacenero)
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();

            // Fecha de emisión
            $table->date('fecha_emision')->index();

            // Estado: borrador, emitida o anulada
            $table->enum('estado', ['borrador', 'emitida', 'anulada'])->default('borrador')->index();

            // Campo opcional de observaciones
            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guia_salidas');
    }
};
