<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->delete();
        DB::table('roles')->insert([
            [
                'name' => 'Administrador',
                'description' => 'Acceso total del sistema',
                'level' => 6,
                'is_active' => true,
                'created_at' => now(), // <-- Añadido
                'updated_at' => now(), // <-- Añadido
            ],
            [
                'name' => 'Decano',
                'description' => null, // <-- Añadido
                'level' => 4,
                'is_active' => true,
                'created_at' => now(), // <-- Añadido
                'updated_at' => now(), // <-- Añadido
            ],
            [
                'name' => 'Docente',
                'description' => null, // <-- Añadido
                'level' => 1,
                'is_active' => true,
                'created_at' => now(), // <-- Añadido
                'updated_at' => now(), // <-- Añadido
            ],
        ]);
    }
}
