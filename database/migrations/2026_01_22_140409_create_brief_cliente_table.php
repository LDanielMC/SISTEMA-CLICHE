<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Prevenir error si la tabla ya existe por un intento fallido anterior
        Schema::dropIfExists('brief_cliente');

        Schema::create('brief_cliente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brief_id')->constrained('briefs')->onDelete('cascade');
            
            // CORRECCIÓN: Usar unsignedBigInteger para coincidir con bigIncrements de clientes
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id_cliente')->on('clientes')->onDelete('cascade');
            
            // Campos específicos de la asignación
            $table->string('estado')->default('pendiente'); // pendiente, recibido
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamp('fecha_ultimo_recordatorio')->nullable();
            
            $table->timestamps();
            
            // Opcional: Evitar duplicar la misma asignación
            // $table->unique(['brief_id', 'cliente_id']); 
        });

        // --- MIGRACIÓN DE DATOS EXISTENTES ---
        // Movemos la información de la tabla briefs a la tabla pivote
        // Verificamos si existen las columnas antes de intentar leerlas (por seguridad)
        if (Schema::hasColumn('briefs', 'id_cliente')) {
            $briefs = DB::table('briefs')->whereNotNull('id_cliente')->get();

            foreach ($briefs as $brief) {
                DB::table('brief_cliente')->insert([
                    'brief_id' => $brief->id,
                    'cliente_id' => $brief->id_cliente,
                    'estado' => $brief->estado ?? 'pendiente',
                    'fecha_envio' => $brief->fecha_envio ?? now(),
                    'fecha_ultimo_recordatorio' => $brief->fecha_ultimo_recordatorio ?? null,
                    'created_at' => $brief->created_at,
                    'updated_at' => $brief->updated_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brief_cliente');
    }
};
