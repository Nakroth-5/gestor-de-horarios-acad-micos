<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Assignment;
use App\Models\AttendanceRecord;
use App\Models\Day;
use Carbon\Carbon;

$date = Carbon::parse('2025-11-12');
$dayName = $date->format('l'); // Tuesday

echo "=== GENERANDO REGISTROS DE ASISTENCIA ===\n";
echo "Fecha: {$date->format('Y-m-d')} ({$dayName})\n\n";

// Buscar el día
$day = Day::where('name', $dayName)->first();

if (!$day) {
    echo "❌ No se encontró el día '{$dayName}'\n";
    echo "Días disponibles: " . Day::pluck('name')->implode(', ') . "\n";
    exit(1);
}

echo "✓ Día encontrado: {$day->name} (ID: {$day->id})\n\n";

// Obtener asignaciones
$assignments = Assignment::whereHas('daySchedule', function ($query) use ($day) {
    $query->where('day_id', $day->id);
})
->with(['userSubject.user', 'userSubject.subject', 'daySchedule.schedule', 'group'])
->get();

echo "Asignaciones encontradas: {$assignments->count()}\n\n";

if ($assignments->isEmpty()) {
    echo "⚠️  No hay clases programadas para {$dayName}\n";
    exit(0);
}

$created = 0;
$existing = 0;

foreach ($assignments as $assignment) {
    $teacher = $assignment->userSubject->user;
    $subject = $assignment->userSubject->subject;
    $schedule = $assignment->daySchedule->schedule;
    
    // Verificar si ya existe
    $existingRecord = AttendanceRecord::where('assignment_id', $assignment->id)
        ->where('user_id', $teacher->id)
        ->whereBetween('created_at', [
            $date->copy()->startOfWeek(),
            $date->copy()->endOfWeek()
        ])
        ->first();

    if ($existingRecord) {
        echo "  ⊙ {$teacher->name} - {$subject->name} ({$schedule->start_time}-{$schedule->end_time}) → Ya existe\n";
        $existing++;
        continue;
    }

    // Crear registro
    AttendanceRecord::create([
        'assignment_id' => $assignment->id,
        'user_id' => $teacher->id,
        'status' => 'absent',
        'scan_time' => null,
        'finish_time' => null,
    ]);

    echo "  ✓ {$teacher->name} - {$subject->name} ({$schedule->start_time}-{$schedule->end_time}) → Creado\n";
    $created++;
}

echo "\n=== RESUMEN ===\n";
echo "✓ Registros creados: {$created}\n";
echo "⊙ Registros existentes: {$existing}\n";
echo "📊 Total de clases: {$assignments->count()}\n";
