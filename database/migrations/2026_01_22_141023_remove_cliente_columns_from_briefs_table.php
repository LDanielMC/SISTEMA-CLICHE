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
        Schema::table('briefs', function (Blueprint $table) {
            // Eliminar claves foráneas primero si existen
            $table->dropForeign(['id_cliente']);
            
            // Eliminar columnas
            $table->dropColumn(['id_cliente', 'estado', 'fecha_envio', 'fecha_ultimo_recordatorio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('briefs', function (Blueprint $table) {
            // Restaurar columnas
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->enum('estado', ['pendiente', 'recibido'])->default('pendiente');
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamp('fecha_ultimo_recordatorio')->nullable();

            // Restaurar clave foránea
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('set null');
        });
    }
};
