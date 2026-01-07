<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizacion_detalle', function (Blueprint $table) {
            $table->id('id_detalle'); // BIGINT UNSIGNED PK AI
            
            // Relación con Cotización Padre (CASCADE)
            $table->foreignId('id_cotizacion')
                  ->constrained('cotizaciones', 'id_cotizacion')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->string('titulo', 100);
            $table->decimal('cantidad', 10, 2)->default(1);
            $table->string('descripcion', 400);
            
            // Precios (DECIMAL 12,2)
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('iva', 12, 2)->default(0); // IVA individual del ítem
            $table->decimal('precio_total', 12, 2);

            $table->timestamps();

            // Índice
            $table->index('id_cotizacion', 'ix_detalle_cot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizacion_detalle');
    }
};