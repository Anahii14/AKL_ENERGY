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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            // Código corto para UI
            $table->string('codigo', 8)->unique()->index();

            // Relaciones
            $table->foreignId('obra_id')
                ->constrained('obras')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('user_id') // solicitante
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Datos del pedido
            $table->date('fecha_requerida')->index();

            $table->enum('estado', [
                'borrador',
                'solicitado',
                'en_proceso_de_compra',
                'aprobado',
                'atendido',
                'entregado',
                'rechazado',
                'cancelado'
            ])->default('solicitado')->index();

            $table->text('observaciones')->nullable();

            $table->unsignedInteger('total_items')->default(0);

            // Índices compuestos útiles en listados
            $table->index(['obra_id', 'estado']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
