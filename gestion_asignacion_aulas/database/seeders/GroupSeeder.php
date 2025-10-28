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
        $groups = [
            "SA", "SB", "SC", "SD", "SE", "SF", "SZ", "F1", "CI", "12", "SG", "SI",
            "SP", "Z1", "Z2", "Z3", "Z4", "Z5", "Z6", "R1", "11", "C1", "SH", "SN",
            "NW", "NX", "SX", "SK", "BI", "X2", "X3", "X4", "SS", "SY", "ER", "SR",
            "W1",
        ];

        foreach (array_unique($groups) as $groupName) {
            if (empty($groupName)) continue;

            Group::firstOrCreate(
                ['name' => $groupName],
            );
        }
    }
}
