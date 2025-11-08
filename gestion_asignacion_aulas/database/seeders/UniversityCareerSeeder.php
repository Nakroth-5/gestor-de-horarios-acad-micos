<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversityCareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('university_careers')->insert([
            [
                'name' => 'Ingeniería Informática',
                'created_at' => now(),
            ],
        ]);
    }
}
