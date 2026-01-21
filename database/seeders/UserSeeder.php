<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'email' => 'luisd.m.c2002@gmail.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
        ]);

        // Usuario Empleado de ejemplo
        User::create([
            'email' => 'empleado@cliche.com',
            'password' => Hash::make('empleado123'),
            'rol' => 'empleado',
        ]);
    }
}
