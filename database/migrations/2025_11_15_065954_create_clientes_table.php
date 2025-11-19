<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id_cliente');
            $table->string('empresa', 180)->nullable();
            $table->string('nombre', 120);
            $table->string('apellido_paterno', 120);
            $table->string('apellido_materno', 120)->nullable();
            $table->string('giro_sector', 150);
            $table->string('correo', 255);
            $table->string('telefono', 30);
            $table->enum('estatus', ['activo','inactivo'])->default('activo');
            $table->date('fecha_registro');
            $table->date('fecha_baja')->nullable();

            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')
                  ->references('id')->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->index('estatus', 'ix_clientes_estatus');
            $table->index('fecha_registro', 'ix_clientes_registro');
            $table->index('fecha_baja', 'ix_clientes_baja');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
