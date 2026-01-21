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
            $table->unsignedBigInteger('id_cliente')->nullable()->after('form_url');
            $table->enum('estado', ['pendiente', 'recibido'])->default('pendiente')->after('id_cliente');
            $table->timestamp('fecha_envio')->nullable()->after('estado');

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('briefs', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropColumn(['id_cliente', 'estado', 'fecha_envio']);
        });
    }
};
