<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\AttendanceRecord;
use App\Models\AcademicManagement;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentAttendances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:mark-absent {--date= : Fecha específica (Y-m-d) o por defecto hoy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marca automáticamente como ausentes las clases que ya pasaron y no tienen registro de asistencia';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Iniciando proceso de marcado automático de ausencias...');
        
        // Obtener fecha a procesar
        $targetDate = $this->option('date') 
            ? Carbon::parse($this->option('date')) 
            : now();
        
        $this->info("📅 Procesando fecha: {$targetDate->format('d/m/Y')}");

        // Obtener periodo académico activo para la fecha
        $academicManagement = AcademicManagement::where('start_date', '<=', $targetDate)
            ->where('end_date', '>=', $targetDate)
            ->first();

        if (!$academicManagement) {
            $this->warn('⚠️  No hay periodo académico activo para la fecha seleccionada.');
            return Command::FAILURE;
        }

        $this->info("📚 Periodo académico: {$academicManagement->name}");

        // Obtener el día de la semana actual
        $dayName = $targetDate->format('l'); // Monday, Tuesday, etc.
        
        $this->info("📆 Día de la semana: {$dayName}");

        // Obtener todas las asignaciones del día actual en el periodo académico
        $assignments = Assignment::with([
            'userSubject.user',
            'userSubject.subject',
            'daySchedule.day',
            'daySchedule.schedule',
            'group'
        ])
        ->whereHas('daySchedule.day', function ($query) use ($dayName) {
            $query->where('name', $dayName);
        })
        ->where('academic_management_id', $academicManagement->id)
        ->get();

        $this->info("📋 Total de clases programadas hoy: {$assignments->count()}");

        $createdCount = 0;
        $skippedCount = 0;
        $weekStart = $targetDate->copy()->startOfWeek();
        $weekEnd = $targetDate->copy()->endOfWeek();

        foreach ($assignments as $assignment) {
            // Verificar si la clase ya pasó
            $classEndTime = Carbon::parse($targetDate->format('Y-m-d') . ' ' . $assignment->daySchedule->schedule->end);
            
            if ($targetDate->lessThan($classEndTime)) {
                // La clase aún no termina, no marcar
                $skippedCount++;
                continue;
            }

            // Verificar si ya existe un registro de asistencia para esta semana
            $existingRecord = AttendanceRecord::where('assignment_id', $assignment->id)
                ->where('user_id', $assignment->userSubject->user_id)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->first();

            if ($existingRecord) {
                // Ya existe registro, no crear duplicado
                $skippedCount++;
                continue;
            }

            // Crear registro de ausencia
            AttendanceRecord::create([
                'assignment_id' => $assignment->id,
                'user_id' => $assignment->userSubject->user_id,
                'status' => 'absent',
                'scan_time' => null,
                'finish_time' => null,
                'created_at' => $targetDate, // Registrar con la fecha de la clase
            ]);

            $createdCount++;
            
            $this->line("  ✓ Ausencia creada: {$assignment->userSubject->user->name} - {$assignment->userSubject->subject->name} - {$assignment->group->name}");
        }

        $this->newLine();
        $this->info("✅ Proceso completado:");
        $this->info("   • Registros de ausencia creados: {$createdCount}");
        $this->info("   • Clases omitidas (ya registradas o no finalizadas): {$skippedCount}");

        return Command::SUCCESS;
    }
}