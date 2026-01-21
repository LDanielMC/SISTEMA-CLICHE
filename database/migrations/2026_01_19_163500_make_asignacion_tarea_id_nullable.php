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
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('asignacion_tarea_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No podemos revertirlo fácilmente a NOT NULL sin asegurar que no haya nulos, 
        // pero para este caso lo dejaremos nullable ya que es lo correcto.
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('asignacion_tarea_id')->nullable(false)->change();
        });
    }
};
