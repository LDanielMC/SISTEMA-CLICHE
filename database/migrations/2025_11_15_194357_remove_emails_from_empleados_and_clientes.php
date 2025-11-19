<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn('correo_contacto');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('correo');
        });
    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->string('correo_contacto', 255)->nullable();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->string('correo', 255)->nullable();
        });
    }
};
