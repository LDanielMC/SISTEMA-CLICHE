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
        Schema::create('evento_recordatorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->enum('tipo_notificacion', ['correo', 'sistema', 'ambos'])->default('ambos');
            $table->integer('minutos_antes');
            $table->boolean('enviado')->default(false);
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamps();
            
            $table->index('evento_id');
            $table->index(['enviado', 'fecha_envio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_recordatorios');
    }
};
