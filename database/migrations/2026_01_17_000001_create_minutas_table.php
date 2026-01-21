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
        Schema::create('minutas', function (Blueprint $table) {
            $table->id('id_minuta');
            $table->unsignedBigInteger('id_cliente');
            $table->date('fecha');
            $table->string('asistentes', 500)->nullable();
            $table->longText('puntos_tratados')->nullable();
            $table->longText('observaciones')->nullable();
            $table->timestamps();

            // Relación con clientes
            $table->foreign('id_cliente')
                ->references('id_cliente')
                ->on('clientes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minutas');
    }
};
