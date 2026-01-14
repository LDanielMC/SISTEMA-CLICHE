<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            // UUID o token para agrupar publicaciones como "un calendario"
            $table->string('lote_calendario', 36)->nullable()->index()->after('cliente_id');
        });
    }

    public function down(): void
    {
        Schema::table('publicaciones', function (Blueprint $table) {
            $table->dropIndex(['lote_calendario']);
            $table->dropColumn('lote_calendario');
        });
    }
};
