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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->unsignedBigInteger('cliente_id');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->text('descripcion');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id_cliente')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
