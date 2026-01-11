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
        Schema::create('bitacora_clientes', function (Blueprint $table) {
            $table->id(); // ID de la bitácora
            
            // Relación con el cliente (usando id_cliente como definiste en tu modelo)
            $table->unsignedBigInteger('id_cliente');
            
            // Acción realizada: 'baja' o 'reactivacion'
            $table->string('accion'); 
            
            // ¿Quién realizó la acción? (Usuario logueado)
            $table->unsignedBigInteger('id_usuario_responsable')->nullable();
            
            // Fecha y hora del evento
            $table->dateTime('fecha_movimiento');
            
            // Timestamps estándar (created_at servirá como respaldo de la fecha)
            $table->timestamps();

            // Llaves foráneas (Opcional, pero recomendado para integridad)
            // Asumiendo que tu tabla se llama 'clientes' y la PK es 'id_cliente'
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('cascade');
            $table->foreign('id_usuario_responsable')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_clientes');
    }
};