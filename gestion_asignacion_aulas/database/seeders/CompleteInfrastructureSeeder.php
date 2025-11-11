<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompleteInfrastructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Módulo
        $moduleId = DB::table('modules')->insertGetId([
            'code' => 236,
            'address' => 'Edificio 36',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Crear Aulas
        $classrooms = [];
        
        // Aulas normales 11-36
        for ($i = 11; $i <= 36; $i++) {
            $classrooms[] = [
                'number' => $i,
                'type' => 'aula',
                'capacity' => 60,
                'module_id' => $moduleId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Aulas de laboratorio 41-46
        for ($i = 41; $i <= 46; $i++) {
            $classrooms[] = [
                'number' => $i,
                'type' => 'laboratorio pcs',
                'capacity' => 60,
                'module_id' => $moduleId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('classrooms')->insert($classrooms);

        // 3. Crear Horarios (Schedules)
        $schedules = [];

        // Horarios para Lunes, Miércoles, Viernes (1h 30min de intervalo)
        $mwfSchedules = [
            ['07:00:00', '08:30:00'],
            ['08:30:00', '10:00:00'],
            ['10:00:00', '11:30:00'],
            ['11:30:00', '13:00:00'],
            ['13:00:00', '14:30:00'],
            ['14:30:00', '16:00:00'],
            ['16:00:00', '17:30:00'],
            ['17:30:00', '19:00:00'],
            ['19:00:00', '20:30:00'],
        ];

        foreach ($mwfSchedules as $schedule) {
            $schedules[] = [
                'start' => $schedule[0],
                'end' => $schedule[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Horarios para Martes y Jueves (2h 15min de intervalo)
        $ttSchedules = [
            ['07:00:00', '09:15:00'],
            ['09:15:00', '11:30:00'],
            ['11:30:00', '13:45:00'],
            ['13:45:00', '16:00:00'],
            ['16:00:00', '18:15:00'],
            ['18:15:00', '20:30:00'],
        ];

        foreach ($ttSchedules as $schedule) {
            $schedules[] = [
                'start' => $schedule[0],
                'end' => $schedule[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar horarios únicos
        foreach ($schedules as $schedule) {
            $exists = DB::table('schedules')
                ->where('start', $schedule['start'])
                ->where('end', $schedule['end'])
                ->exists();

            if (!$exists) {
                DB::table('schedules')->insert($schedule);
            }
        }

        // 4. Crear Day Schedules (combinaciones día-horario)
        $days = DB::table('days')->get();
        $allSchedules = DB::table('schedules')->get();

        $daySchedules = [];

        foreach ($days as $day) {
            // Monday, Wednesday, Friday usan horarios de 1h 30min
            if (in_array(trim($day->name), ['Monday', 'Wednesday', 'Friday'])) {
                foreach ($allSchedules as $schedule) {
                    // Filtrar solo horarios de 1h 30min (diferencia de 90 minutos)
                    $start = strtotime($schedule->start);
                    $end = strtotime($schedule->end);
                    $diff = ($end - $start) / 60; // diferencia en minutos

                    if ($diff == 90) {
                        $daySchedules[] = [
                            'day_id' => $day->id,
                            'schedule_id' => $schedule->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            // Tuesday, Thursday usan horarios de 2h 15min
            elseif (in_array(trim($day->name), ['Tuesday', 'Thursday'])) {
                foreach ($allSchedules as $schedule) {
                    // Filtrar solo horarios de 2h 15min (diferencia de 135 minutos)
                    $start = strtotime($schedule->start);
                    $end = strtotime($schedule->end);
                    $diff = ($end - $start) / 60; // diferencia en minutos

                    if ($diff == 135) {
                        $daySchedules[] = [
                            'day_id' => $day->id,
                            'schedule_id' => $schedule->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            // Saturday, Sunday pueden usar cualquier horario o ninguno
            elseif (in_array(trim($day->name), ['Saturday'])) {
                foreach ($allSchedules as $schedule) {
                    // Sábado usa horarios de 1h 30min
                    $start = strtotime($schedule->start);
                    $end = strtotime($schedule->end);
                    $diff = ($end - $start) / 60;

                    if ($diff == 90) {
                        $daySchedules[] = [
                            'day_id' => $day->id,
                            'schedule_id' => $schedule->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Insertar day_schedules únicos
        foreach ($daySchedules as $ds) {
            $exists = DB::table('day_schedules')
                ->where('day_id', $ds['day_id'])
                ->where('schedule_id', $ds['schedule_id'])
                ->exists();

            if (!$exists) {
                DB::table('day_schedules')->insert($ds);
            }
        }
    }
}
