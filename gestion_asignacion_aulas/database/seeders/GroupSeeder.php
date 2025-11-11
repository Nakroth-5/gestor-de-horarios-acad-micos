<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el primer periodo académico y primera carrera
        $academicPeriod = \DB::table('academic_management')->first();
        $careers = \DB::table('university_careers')->get();
        
        if (!$academicPeriod || $careers->isEmpty()) {
            // Si no hay periodo académico o carreras, crear grupos sin asignación
            $groups = [
                "SA", "SB", "SC", "SD", "SE", "SF", "SZ", "F1", "CI", "12", "SG", "SI",
                "SP", "Z1", "Z2", "Z3", "Z4", "Z5", "Z6", "R1", "11", "C1", "SH", "SN",
                "NW", "NX", "SX", "SK", "BI", "X2", "X3", "X4", "SS", "SY", "ER", "SR",
                "W1",
            ];

            foreach (array_unique($groups) as $groupName) {
                if (empty($groupName)) continue;
                Group::firstOrCreate(['name' => $groupName]);
            }
            return;
        }

        $groups = [
            "SA", "SB", "SC", "SD", "SE", "SF", "SZ", "F1", "CI", "12", "SG", "SI",
            "SP", "Z1", "Z2", "Z3", "Z4", "Z5", "Z6", "R1", "11", "C1", "SH", "SN",
            "NW", "NX", "SX", "SK", "BI", "X2", "X3", "X4", "SS", "SY", "ER", "SR",
            "W1",
        ];

        // Asignar grupos rotativamente a las carreras disponibles
        $careerIndex = 0;
        $totalCareers = $careers->count();

        foreach (array_unique($groups) as $groupName) {
            if (empty($groupName)) continue;

            Group::firstOrCreate(
                ['name' => $groupName],
                [
                    'academic_management_id' => $academicPeriod->id,
                    'university_career_id' => $careers[$careerIndex]->id,
                ]
            );

            // Rotar entre las carreras disponibles
            $careerIndex = ($careerIndex + 1) % $totalCareers;
        }
    }
}
