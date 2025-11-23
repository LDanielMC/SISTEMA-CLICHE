<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('info_fiscal', function (Blueprint $table) {
            $table->id('id_fiscal'); // Tu Primary Key personalizada
            
            // Relación con clientes
            $table->foreignId('id_cliente')
                  ->unique() // Garantiza relacion 1 a 1 (UQ key)
                  ->constrained('clientes', 'id_cliente') // Tabla y PK de clientes
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->string('rfc', 20)->index(); // Index agregado
            $table->string('razon_social', 255);
            $table->string('direccion_fiscal', 400);
            $table->string('regimen', 120);
            $table->string('telefono_fiscal', 30);
            $table->string('correo_fiscal', 255);

            // Mapeo de tus fechas personalizadas
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('fecha_actualiza')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('info_fiscal');
    }
};