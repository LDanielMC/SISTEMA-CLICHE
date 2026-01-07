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
            // Agregamos los campos después del cliente para mantener orden
            $table->string('titulo_cotizacion', 200)->after('id_cliente')->nullable(); 
            $table->text('texto_introduccion')->after('titulo_cotizacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn(['titulo_cotizacion', 'texto_introduccion']);
        });
    }
};
