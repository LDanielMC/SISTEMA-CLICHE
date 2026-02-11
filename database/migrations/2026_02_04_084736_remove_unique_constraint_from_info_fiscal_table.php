<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Elimina la restricción de unicidad para permitir múltiples info fiscales por cliente.
     */
    public function up(): void
    {
        Schema::table('info_fiscal', function (Blueprint $table) {
            // Eliminar el índice único en id_cliente
            $table->dropUnique('info_fiscal_id_cliente_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('info_fiscal', function (Blueprint $table) {
            // Restaurar el índice único (si se hace rollback)
            $table->unique('id_cliente');
        });
    }
};
