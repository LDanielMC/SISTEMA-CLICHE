<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id('id_cotizacion'); // BIGINT UNSIGNED PK AI
            
            // Relación con Clientes (RESTRICT: No borrar cliente si tiene cotizaciones)
            $table->foreignId('id_cliente')
                  ->constrained('clientes', 'id_cliente')
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); 

            $table->date('fecha');
            $table->unsignedTinyInteger('vencimiento_dias')->default(7);
            
            // Totales (DECIMAL 12,2)
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('iva_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            
            $table->text('notas')->nullable();
            
            // Enum
            $table->enum('estatus', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');

            // Timestamps de Laravel (created_at, updated_at)
            $table->timestamps();

            // Índices personalizados que pediste
            $table->index(['id_cliente', 'fecha'], 'ix_cot_cliente_fecha');
            $table->index('estatus', 'ix_cot_estatus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
