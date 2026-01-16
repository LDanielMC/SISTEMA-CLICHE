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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreignId('creado_por')->constrained('users')->onDelete('cascade');
            
            $table->foreign('cliente_id')->references('id_cliente')->on('clientes')->onDelete('set null');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('lugar')->nullable();
            $table->text('notas')->nullable();
            $table->string('color')->default('#3B82F6');
            
            // Recurrencia
            $table->enum('recurrencia', ['ninguna', 'diaria', 'semanal', 'mensual', 'anual'])->default('ninguna');
            $table->json('recurrencia_config')->nullable(); // Para configuración avanzada (días de la semana, etc.)
            $table->date('recurrencia_hasta')->nullable();
            
            // Google Calendar
            $table->string('google_event_id')->nullable()->unique();
            $table->boolean('sincronizado_google')->default(false);
            $table->timestamp('ultima_sincronizacion')->nullable();
            
            $table->timestamps();
            
            $table->index(['fecha', 'hora_inicio']);
            $table->index('cliente_id');
            $table->index('creado_por');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
