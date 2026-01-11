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
        Schema::create('asignaciones_tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->unsignedBigInteger('empleado_id');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->default('media');
            $table->date('fecha_limite');
            $table->enum('estado_empleado', ['asignada', 'en_proceso', 'terminada'])->default('asignada');
            $table->enum('estado_admin', ['pendiente', 'completa', 'parcialmente_completa', 'incompleta'])->nullable();
            $table->string('evidencia_path')->nullable();
            $table->timestamp('fecha_entrega')->nullable();
            $table->text('notas_admin')->nullable();
            $table->timestamps();

            $table->foreign('empleado_id')->references('id_empleado')->on('empleados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones_tareas');
    }
};
