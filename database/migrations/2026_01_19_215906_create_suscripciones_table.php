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
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id('idSuscripcion');
            $table->foreignId('idCategoria')
                  ->constrained('categorias_suscripcion', 'idCategoria')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->string('nombre_servicio', 150);
            $table->date('fecha_inicio');
            $table->decimal('costo', 10, 2);
            $table->enum('periodicidad', ['mensual', 'anual']);
            $table->date('fecha_vencimiento');
            $table->json('dias_recordatorio')->nullable(); // [15, 7, 3]
            $table->enum('nivel_uso', ['bajo', 'medio', 'alto'])->default('medio');
            $table->text('observaciones')->nullable();
            $table->enum('estatus', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};
