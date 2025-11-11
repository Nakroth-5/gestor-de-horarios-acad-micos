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
                'name' => 'Ingeniería en Sistemas',
                'code' => '187-4',
                'study_level' => 'Licenciatura',
                'duration_years' => 5,
                'faculty' => 'Facultad de Ciencias y Tecnología',
                'language' => 'Español',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ingeniería Informática',
                'code' => '187-5',
                'study_level' => 'Licenciatura',
                'duration_years' => 5,
                'faculty' => 'Facultad de Ciencias y Tecnología',
                'language' => 'Español',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ingeniería Civil',
                'code' => '181-2',
                'study_level' => 'Licenciatura',
                'duration_years' => 5,
                'faculty' => 'Facultad de Ingeniería',
                'language' => 'Español',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
