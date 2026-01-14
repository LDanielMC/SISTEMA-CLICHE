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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('asignacion_tarea_id')->nullable()->constrained('asignaciones_tareas')->onDelete('cascade');
            $table->enum('tipo', ['tarea_asignada', 'tarea_en_proceso', 'tarea_terminada', 'tarea_evaluada', 'tarea_evaluada_completa', 'tarea_evaluada_parcial', 'tarea_evaluada_incompleta', 'recordatorio_evento']);
            $table->string('titulo');
            $table->text('mensaje');
            $table->boolean('leida')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
