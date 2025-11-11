<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('academic_management')->delete();
        DB::table('academic_management')->insert([
            [
                'name' => 'Gestión 2/2025',
                'start_date' => '2025-08-01', // Inicio del segundo semestre 2025
                'end_date' => '2025-12-31',   // Fin del año
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
