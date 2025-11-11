<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            DaySeeder::class,
            SubjectSeeder::class,
            AcademicManagementSeeder::class,
            UniversityCareerSeeder::class,
            GroupSeeder::class,
            CompleteInfrastructureSeeder::class, // Crea módulos, aulas, horarios y day_schedules
            RoleUserSeeder::class,
        ]);
    }
}
