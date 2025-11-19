<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->bigIncrements('id_empleado');
            $table->string('nombre', 120);
            $table->string('apellido_paterno', 120);
            $table->string('apellido_materno', 120)->nullable();
            $table->string('correo_contacto', 255);
            $table->string('telefono', 30);
            $table->string('puesto', 120);
            $table->date('fecha_ingreso');
            $table->enum('estatus', ['activo','inactivo','baja'])->default('activo');
            $table->date('fecha_baja')->nullable();

            // 🔗 Relación con users (equivale a tu id_usuario -> usuarios.id_usuario)
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')
                  ->references('id')->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->index('id_usuario', 'ix_empleado_usuario');
            $table->index('estatus', 'ix_empleado_estatus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
