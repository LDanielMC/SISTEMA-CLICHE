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
        Schema::table('cotizaciones', function (Blueprint $table) {
            // Agregamos columna para el porcentaje (ej: 10, 16)
            // 'after' indica después de qué columna se insertará (para orden visual)
            $table->decimal('porcentaje_isr', 5, 2)->default(0)->after('iva_total');
            
            // Agregamos columna para el monto calculado
            $table->decimal('retencion_isr', 15, 2)->default(0)->after('porcentaje_isr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            // Si revertimos la migración, borramos estas columnas
            $table->dropColumn(['porcentaje_isr', 'retencion_isr']);
        });
    }
};