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
        Schema::create('suscripcion_recordatorios_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idSuscripcion')
                  ->constrained('suscripciones', 'idSuscripcion')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->date('fecha_recordatorio');
            $table->integer('dias_restantes');
            $table->timestamps();
            
            // Índice único para evitar duplicados del mismo día
            $table->unique(['idSuscripcion', 'fecha_recordatorio'], 'sus_rec_log_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripcion_recordatorios_log');
    }
};
