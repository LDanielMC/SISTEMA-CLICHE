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
        Schema::table('acuerdos', function (Blueprint $table) {
            // Eliminar la clave foránea existente usando el nombre exacto de la constraint
            $table->dropForeign('acuerdos_id_minuta_foreign');
            
            // Recrear la clave foránea con onDelete('cascade')
            $table->foreign('id_minuta')
                ->references('id_minuta')
                ->on('minutas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acuerdos', function (Blueprint $table) {
            // Eliminar la clave foránea con cascade
            $table->dropForeign('acuerdos_id_minuta_foreign');
            
            // Recrear la clave foránea sin cascade (estado original)
            $table->foreign('id_minuta')
                ->references('id_minuta')
                ->on('minutas')
                ->onDelete('cascade');
        });
    }
};
