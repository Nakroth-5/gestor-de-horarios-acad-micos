<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the 'Docente' role exists in the roles table and get its id
        $role = DB::table('roles')->where('name', 'Docente')->first();

        if (! $role) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => 'Docente',
                'description' => null,
                'level' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $roleId = $role->id;
            // Optionally ensure some fields are up-to-date
            DB::table('roles')->where('id', $roleId)->update([
                'description' => $role->description ?? null,
                'level' => 1,
                'is_active' => true,
                'updated_at' => now(),
            ]);
        }

        // Get all user IDs
        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            // No users to assign
            return;
        }

        // Remove existing entries for this role to avoid duplicates
        DB::table('role_user')->where('role_id', $roleId)->delete();

        $now = now();
        $inserts = [];
        foreach ($userIds as $uid) {
            $inserts[] = [
                'user_id' => $uid,
                'role_id' => $roleId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insert in chunks in case there are many users
        foreach (array_chunk($inserts, 500) as $chunk) {
            DB::table('role_user')->insert($chunk);
        }
    }
}