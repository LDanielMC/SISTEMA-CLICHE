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
        Schema::create('publicaciones', function (Blueprint $table) {
                // 1. Identificador único (renombrado a idPublicacion como pediste)
                $table->id('idPublicacion'); 

                // 2. Relaciones (Foreign Keys)
                // Relación con Cliente
                $table->foreignId('cliente_id')
                    ->constrained('clientes') // Asegúrate que tu tabla se llame 'clientes'
                    ->onDelete('cascade');
                
                // Relación con Plataforma (idPlataforma)
                $table->foreignId('plataforma_id')
                    ->constrained('plataformas');

                // Relación con Formato (idFormato)
                $table->foreignId('formato_id')
                    ->constrained('formatos');

                // 3. Datos del Calendario
                $table->date('fecha'); // Solo fecha, sin hora
                
                // 4. Contenido
                $table->text('copy')->nullable(); // Descripción y hashtags
                $table->text('arte')->nullable(); // Instrucciones visuales del video/imagen
                
                // 5. Estado
                $table->enum('estatus', ['Pendiente', 'Publicado', 'Reprogramar'])
                    ->default('Pendiente');

                $table->timestamps(); // created_at, updated_at
            });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicaciones');
    }
};
