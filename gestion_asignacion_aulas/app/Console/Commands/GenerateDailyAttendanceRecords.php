<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\AttendanceRecord;
use App\Models\Day;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateDailyAttendanceRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:generate-daily
                            {--date= : Fecha específica en formato Y-m-d (opcional, por defecto hoy)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera registros de asistencia para todas las clases programadas del día con estado "absent" por defecto';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : now();
        $dayName = $date->format('l'); // Monday, Tuesday, etc.
        
        $this->info("=== GENERANDO REGISTROS DE ASISTENCIA ===");
        $this->info("Fecha: {$date->format('Y-m-d')} ({$dayName})");

        // Buscar el día en la base de datos
        $day = Day::where('name', $dayName)->first();
        
        if (!$day) {
            $this->error("❌ No se encontró el día '{$dayName}' en la base de datos.");
            $this->warn("Días disponibles: " . Day::pluck('name')->implode(', '));
            return 1;
        }
        
        $this->info("✓ Día encontrado: {$day->name} (ID: {$day->id})");

        // Obtener todas las asignaciones para este día
        $assignments = Assignment::whereHas('daySchedule', function ($query) use ($day) {
            $query->where('day_id', $day->id);
        })
        ->with(['userSubject.user', 'userSubject.subject', 'daySchedule.schedule', 'group'])
        ->get();

        $this->info("Asignaciones encontradas: {$assignments->count()}");

        if ($assignments->isEmpty()) {
            $this->warn("⚠️  No hay clases programadas para {$dayName}.");
            return 0;
        }

        $created = 0;
        $existing = 0;

        foreach ($assignments as $assignment) {
            $teacher = $assignment->userSubject->user;
            $subject = $assignment->userSubject->subject;
            $schedule = $assignment->daySchedule->schedule;
            
            // Verificar si ya existe un registro de asistencia para esta semana
            $existingRecord = AttendanceRecord::where('assignment_id', $assignment->id)
                ->where('user_id', $teacher->id)
                ->whereBetween('created_at', [
                    $date->copy()->startOfWeek(),
                    $date->copy()->endOfWeek()
                ])
                ->first();

            if ($existingRecord) {
                $this->warn("  ⊙ {$teacher->name} - {$subject->name} ({$schedule->start_time}-{$schedule->end_time}) → Ya existe");
                $existing++;
                continue;
            }

            // Crear registro de asistencia con estado "absent"
            AttendanceRecord::create([
                'assignment_id' => $assignment->id,
                'user_id' => $teacher->id,
                'status' => 'absent',
                'scan_time' => null,
                'finish_time' => null,
            ]);

            $this->info("  ✓ {$teacher->name} - {$subject->name} ({$schedule->start_time}-{$schedule->end_time}) → Creado");
            $created++;
        }

        $this->newLine();
        $this->info("=== RESUMEN ===");
        $this->info("✓ Registros creados: {$created}");
        $this->info("⊙ Registros existentes (omitidos): {$existing}");
        $this->info("📊 Total de clases programadas: " . $assignments->count());

        return 0;
    }
}
