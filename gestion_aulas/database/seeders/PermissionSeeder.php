<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpia la tabla antes de llenarla (opcional, pero recomendado)
        DB::table('permissions')->delete();

        DB::table('permissions')->insert([

            // --- MÓDULO DE USUARIOS ---
            [
                'name' => 'Ver Usuarios',
                'action' => 'user.view',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Crear Usuarios',
                'action' => 'user.create',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Editar Usuarios',
                'action' => 'user.edit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eliminar Usuarios',
                'action' => 'user.delete',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- MÓDULO DE ROLES ---
            [
                'name' => 'Ver Roles',
                'action' => 'role.view',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Crear Roles',
                'action' => 'role.create',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Editar Roles',
                'action' => 'role.edit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eliminar Roles',
                'action' => 'role.delete',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- MÓDULO DE DASHBOARD ---
            [
                'name' => 'Ver Dashboard',
                'action' => 'dashboard.view',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
