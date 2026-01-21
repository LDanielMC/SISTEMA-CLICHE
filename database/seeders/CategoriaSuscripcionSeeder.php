<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoriaSuscripcion;

class CategoriaSuscripcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Hosting y Dominios', 'estatus' => 'activo'],
            ['nombre' => 'Software y Herramientas', 'estatus' => 'activo'],
            ['nombre' => 'Servicios en la Nube', 'estatus' => 'activo'],
            ['nombre' => 'Marketing Digital', 'estatus' => 'activo'],
            ['nombre' => 'Diseño y Creatividad', 'estatus' => 'activo'],
            ['nombre' => 'Comunicación y Colaboración', 'estatus' => 'activo'],
            ['nombre' => 'Seguridad y Respaldos', 'estatus' => 'activo'],
            ['nombre' => 'Bases de Datos', 'estatus' => 'activo'],
            ['nombre' => 'Licencias de Software', 'estatus' => 'activo'],
            ['nombre' => 'Otros', 'estatus' => 'activo'],
        ];

        foreach ($categorias as $categoria) {
            CategoriaSuscripcion::create($categoria);
        }
    }
}
