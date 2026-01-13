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
        Schema::create('plataformas', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('nombre'); // Ej: Instagram, Facebook
            $table->string('icono')->nullable(); // Opcional: para guardar clase de icono o url
            $table->boolean('activo')->default(true); // Para ocultar plataformas viejas sin borrarlas
            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plataformas');
    }
};
