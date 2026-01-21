<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaTareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Diseño Gráfico', 'descripcion' => 'Tareas relacionadas con diseño visual'],
            ['nombre' => 'Desarrollo Web', 'descripcion' => 'Tareas de programación y desarrollo'],
            ['nombre' => 'Marketing Digital', 'descripcion' => 'Tareas de publicidad y marketing'],
            ['nombre' => 'Redes Sociales', 'descripcion' => 'Gestión de redes sociales'],
            ['nombre' => 'Fotografía', 'descripcion' => 'Sesiones fotográficas y edición'],
            ['nombre' => 'Video', 'descripcion' => 'Producción y edición de video'],
            ['nombre' => 'Administración', 'descripcion' => 'Tareas administrativas generales'],
            ['nombre' => 'Atención al Cliente', 'descripcion' => 'Soporte y atención a clientes'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
