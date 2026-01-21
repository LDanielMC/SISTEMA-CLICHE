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
        Schema::create('suscripcion_renovaciones', function (Blueprint $table) {
            $table->id('idRenovacion');
            $table->foreignId('idSuscripcion')
                  ->constrained('suscripciones', 'idSuscripcion')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->date('fecha_renovacion');
            $table->decimal('costo_ciclo', 10, 2);
            $table->date('fecha_vencimiento_anterior');
            $table->date('fecha_vencimiento_nueva');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripcion_renovaciones');
    }
};
