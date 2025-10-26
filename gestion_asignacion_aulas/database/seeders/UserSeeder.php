<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            [
                'name' => 'Zata',
                'code' => 111,
                'last_name' => 'Rodríguez',
                'phone' => '77712345',
                'address' => 'Santa Cruz',
                'email' => 'evertha304@gmail.com',
                'document_type' => 'CI',
                'document_number' => '1234567',
                'is_active' => true,
                'password' => Hash::make('zata'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
