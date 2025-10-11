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
        Schema::create('materiales', function (Blueprint $table) {
            $table->id();

            // Relación (opcional) con proveedor
            $table->foreignId('proveedor_id')
                  ->nullable()
                  ->constrained('proveedores')
                  ->nullOnDelete()
                  ->index();

            $table->string('nombre', 180)->index();
            $table->string('codigo', 50)->unique();

            $table->text('descripcion')->nullable();
            $table->string('unidad', 30); 

            $table->decimal('stock_actual', 12, 3)->default(0);
            $table->decimal('stock_minimo', 12, 3)->default(10); 
            $table->decimal('precio_unitario', 12, 2)->default(0);

            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiales');
    }
};
