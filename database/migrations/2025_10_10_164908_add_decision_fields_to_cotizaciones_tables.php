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
        // ===== Tabla de grúas =====
        Schema::table('cotizaciones_gruas', function (Blueprint $table) {
            // Si no existe la columna estado, asegúrate de tenerla en tu tabla
            $table->string('estado')->default('pendiente')->change();
            $table->text('decision_motivo')->nullable()->after('estado');
            $table->unsignedBigInteger('decision_por')->nullable()->after('decision_motivo');
            $table->timestamp('decision_fecha')->nullable()->after('decision_por');
        });

        // ===== Tabla de eléctricas =====
        Schema::table('cotizaciones_electrico', function (Blueprint $table) {
            $table->string('estado')->default('pendiente')->change();
            $table->text('decision_motivo')->nullable()->after('estado');
            $table->unsignedBigInteger('decision_por')->nullable()->after('decision_motivo');
            $table->timestamp('decision_fecha')->nullable()->after('decision_por');
        });
    }

    /**
     * Reversión de los cambios.
     */
    public function down(): void
    {
        Schema::table('cotizaciones_gruas', function (Blueprint $table) {
            $table->dropColumn(['decision_motivo', 'decision_por', 'decision_fecha']);
        });

        Schema::table('cotizaciones_electrico', function (Blueprint $table) {
            $table->dropColumn(['decision_motivo', 'decision_por', 'decision_fecha']);
        });
    }
};
