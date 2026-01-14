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
        Schema::create('evento_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->enum('tipo', ['cliente', 'empleado', 'externo']);
            $table->unsignedBigInteger('referencia_id')->nullable(); // ID del cliente o empleado
            $table->string('nombre');
            $table->string('correo');
            $table->boolean('confirmado')->default(false);
            $table->timestamps();
            
            $table->index('evento_id');
            $table->index(['tipo', 'referencia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_participantes');
    }
};
