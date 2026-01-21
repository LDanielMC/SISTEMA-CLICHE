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
        Schema::table('minutas', function (Blueprint $table) {
            $table->string('titulo', 255)->nullable()->after('id_cliente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('minutas', function (Blueprint $table) {
            $table->dropColumn('titulo');
        });
    }
};
