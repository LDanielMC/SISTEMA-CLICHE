<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\User;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'luisd.m.c2002@gmail.com')->first();
        $empleado = User::where('email', 'empleado@cliche.com')->first();

        // Empleado Admin
        Empleado::create([
            'id_usuario' => $admin->id,
            'nombre' => 'Luis Daniel',
            'apellido_paterno' => 'Martínez',
            'apellido_materno' => 'Cruz',
            'telefono' => '1234567890',
            'puesto' => 'Director General',
            'fecha_ingreso' => '2024-01-01',
            'estatus' => 'activo',
        ]);

        // Empleado regular
        Empleado::create([
            'id_usuario' => $empleado->id,
            'nombre' => 'María',
            'apellido_paterno' => 'González',
            'apellido_materno' => 'López',
            'telefono' => '0987654321',
            'puesto' => 'Diseñadora Gráfica',
            'fecha_ingreso' => '2024-06-15',
            'estatus' => 'activo',
        ]);
    }
}
