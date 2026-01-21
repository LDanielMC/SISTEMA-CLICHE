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
        Schema::create('acuerdos', function (Blueprint $table) {
            $table->id('id_acuerdo');
            $table->unsignedBigInteger('id_minuta');
            $table->longText('acuerdo');
            $table->enum('estatus', ['pendiente', 'completado', 'cancelado'])->default('pendiente');
            $table->date('fecha_limite')->nullable();
            $table->timestamps();

            // Relación con minutas
            $table->foreign('id_minuta')
                ->references('id_minuta')
                ->on('minutas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acuerdos');
    }
};
