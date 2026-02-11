<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rellenar registros existentes que tengan NULL
        DB::table('cotizaciones')
            ->whereNull('titulo_cotizacion')
            ->update(['titulo_cotizacion' => 'Sin título']);

        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->string('titulo_cotizacion', 200)->nullable(false)->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->string('titulo_cotizacion', 200)->nullable()->change();
        });
    }
};
