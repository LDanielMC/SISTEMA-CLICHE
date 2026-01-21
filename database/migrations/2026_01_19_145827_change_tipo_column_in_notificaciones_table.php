<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificamos la columna ENUM usando SQL directo para mayor compatibilidad
        DB::statement("ALTER TABLE notificaciones MODIFY COLUMN tipo ENUM('tarea_asignada', 'tarea_en_proceso', 'tarea_terminada', 'tarea_evaluada', 'tarea_evaluada_completa', 'tarea_evaluada_parcial', 'tarea_evaluada_incompleta', 'recordatorio_evento', 'alerta') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertimos a los valores originales
        DB::statement("ALTER TABLE notificaciones MODIFY COLUMN tipo ENUM('tarea_asignada', 'tarea_en_proceso', 'tarea_terminada', 'tarea_evaluada', 'tarea_evaluada_completa', 'tarea_evaluada_parcial', 'tarea_evaluada_incompleta', 'recordatorio_evento') NOT NULL");
    }
};
