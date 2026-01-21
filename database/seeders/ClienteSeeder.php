<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Los clientes requieren id_usuario, así que los crearemos manualmente
        // cuando sea necesario desde la interfaz
        // Por ahora, dejamos este seeder vacío para no causar errores
        
        // Si necesitas clientes de prueba, créalos desde la interfaz web
        // o modifica este seeder para asignar usuarios existentes
    }
}
