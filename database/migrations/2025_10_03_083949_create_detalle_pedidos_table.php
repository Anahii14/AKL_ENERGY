<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pedido_id')
                ->constrained('pedidos')
                ->cascadeOnDelete();

            $table->foreignId('material_id')  
                ->constrained('materiales'); 

            $table->decimal('cantidad', 12, 2);
            $table->unsignedDecimal('precio_unitario', 10, 2);
            $table->unsignedDecimal('descuento', 10, 2)->default(0);
            $table->unsignedDecimal('subtotal', 12, 2)
                ->storedAs('(cantidad * precio_unitario) - descuento');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedidos');
    }
};
