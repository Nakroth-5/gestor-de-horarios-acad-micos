<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeStrings = [
            "07:00-08:30", "08:30-10:00", "10:00-11:30", "11:30-13:00",
            "13:00-14:30", "14:30-16:00", "16:00-17:30", "17:30-19:00",
            "19:00-20:30", "20:30-22:00", "22:00-23:30", "23:30-01:00",
            "07:00-09:15", "09:15-11:30", "11:30-13:45", "13:45-16:00",
            "16:00-18:15", "18:15-20:30", "20:30-22:45", "22:45-01:00",
        ];

        $uniqueSchedules = [];
        foreach ($timeStrings as $raw) {
            $cleaned = str_replace([':', ' '], '', $raw); // Remove colons and spaces
            $cleaned = preg_replace('/(\d{2})(\d{2})/', '$1:$2', $cleaned); // Add colons back
            $parts = explode('-', $cleaned);

            if (count($parts) === 2) {
                try {
                    $start = Carbon::parse($parts[0])->format('H:i:s');
                    $end = Carbon::parse($parts[1])->format('H:i:s');
                    $key = "$start-$end";

                    if (!isset($uniqueSchedules[$key])) {
                        $uniqueSchedules[$key] = ['start' => $start, 'end' => $end];
                    }
                } catch (\Exception $e) {
                    // Skip invalid formats like ":00-830:"
                }
            }
        }

        foreach ($uniqueSchedules as $schedule) {
            Schedule::firstOrCreate($schedule);
        }
    }
}
