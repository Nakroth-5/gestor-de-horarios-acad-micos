<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AttendanceRecord;
use App\Models\Classroom;
use App\Models\AcademicManagement;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Mostrar vista principal de reportes
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Reporte de Horarios Semanales
     */
    public function weeklySchedules(Request $request)
    {
        $academicManagementId = $request->input('academic_management_id');
        $groupId = $request->input('group_id');
        $format = $request->input('format', 'view'); // view, pdf, excel

        // Obtener periodo académico activo o el seleccionado
        $academicManagement = $academicManagementId 
            ? AcademicManagement::find($academicManagementId)
            : AcademicManagement::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

        if (!$academicManagement) {
            return back()->with('error', 'No hay periodo académico activo.');
        }

        // Construir query base
        $query = Assignment::with([
            'userSubject.subject',
            'userSubject.user',
            'group',
            'daySchedule.day',
            'daySchedule.schedule',
            'classroom.infrastructure'
        ])->where('academic_management_id', $academicManagement->id);

        // Filtrar por grupo si se especifica
        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        $assignments = $query->get();

        // Organizar por día y horario
        $scheduleData = $this->organizeScheduleByDay($assignments);

        // Obtener listas para filtros
        $academicManagements = AcademicManagement::orderBy('start_date', 'desc')->get();
        $groups = Group::where('is_active', true)->orderBy('name')->get();

        if ($format === 'pdf') {
            return $this->generateWeeklySchedulePDF($scheduleData, $academicManagement, $groupId);
        }

        return view('reports.weekly-schedules', compact(
            'scheduleData',
            'academicManagement',
            'academicManagements',
            'groups',
            'groupId'
        ));
    }

    /**
     * Reporte de Asistencia por Docente y Grupo
     */
    public function attendanceReport(Request $request)
    {
        $userId = $request->input('user_id');
        $groupId = $request->input('group_id');
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $format = $request->input('format', 'view');

        // Query base de registros de asistencia
        $query = AttendanceRecord::with([
            'assignment.userSubject.subject',
            'assignment.userSubject.user',
            'assignment.group',
            'assignment.daySchedule.day',
            'assignment.daySchedule.schedule',
            'user'
        ])->whereBetween('created_at', [$startDate, $endDate]);

        // Filtros
        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($groupId) {
            $query->whereHas('assignment', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            });
        }

        $attendanceRecords = $query->orderBy('created_at', 'desc')->get();

        // Calcular estadísticas
        $statistics = $this->calculateAttendanceStatistics($attendanceRecords);

        // Listas para filtros
        $teachers = User::role('Docente')->orderBy('name')->get();
        $groups = Group::where('is_active', true)->orderBy('name')->get();

        if ($format === 'pdf') {
            return $this->generateAttendancePDF($attendanceRecords, $statistics, $startDate, $endDate);
        }

        return view('reports.attendance', compact(
            'attendanceRecords',
            'statistics',
            'teachers',
            'groups',
            'userId',
            'groupId',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Reporte de Aulas Disponibles
     */
    public function availableClassrooms(Request $request)
    {
        $dayId = $request->input('day_id');
        $scheduleId = $request->input('schedule_id');
        $date = $request->input('date', now()->format('Y-m-d'));
        $format = $request->input('format', 'view');

        // Obtener periodo académico activo
        $academicManagement = AcademicManagement::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        if (!$academicManagement) {
            return back()->with('error', 'No hay periodo académico activo para la fecha seleccionada.');
        }

        // Obtener todas las aulas
        $allClassrooms = Classroom::with('infrastructure')->where('is_active', true)->get();

        // Obtener aulas ocupadas
        $occupiedQuery = Assignment::with([
            'classroom',
            'userSubject.subject',
            'userSubject.user',
            'group',
            'daySchedule.schedule'
        ])->where('academic_management_id', $academicManagement->id);

        if ($dayId) {
            $occupiedQuery->whereHas('daySchedule', function ($q) use ($dayId) {
                $q->where('day_id', $dayId);
            });
        }

        if ($scheduleId) {
            $occupiedQuery->whereHas('daySchedule', function ($q) use ($scheduleId) {
                $q->where('schedule_id', $scheduleId);
            });
        }

        $occupiedClassrooms = $occupiedQuery->get()->pluck('classroom_id')->toArray();

        // Clasificar aulas
        $availableClassrooms = $allClassrooms->whereNotIn('id', $occupiedClassrooms);
        $occupiedClassroomsData = $allClassrooms->whereIn('id', $occupiedClassrooms);

        // Obtener datos de las asignaciones para aulas ocupadas
        $occupiedDetails = [];
        foreach ($occupiedClassroomsData as $classroom) {
            $assignment = Assignment::with([
                'userSubject.subject',
                'userSubject.user',
                'group',
                'daySchedule.day',
                'daySchedule.schedule'
            ])->where('classroom_id', $classroom->id)
              ->where('academic_management_id', $academicManagement->id);

            if ($dayId) {
                $assignment->whereHas('daySchedule', function ($q) use ($dayId) {
                    $q->where('day_id', $dayId);
                });
            }

            if ($scheduleId) {
                $assignment->whereHas('daySchedule', function ($q) use ($scheduleId) {
                    $q->where('schedule_id', $scheduleId);
                });
            }

            $occupiedDetails[$classroom->id] = $assignment->first();
        }

        // Datos para filtros
        $days = DB::table('days')->get();
        $schedules = DB::table('schedules')->orderBy('start')->get();

        if ($format === 'pdf') {
            return $this->generateAvailableClassroomsPDF(
                $availableClassrooms, 
                $occupiedClassroomsData, 
                $occupiedDetails,
                $date
            );
        }

        return view('reports.available-classrooms', compact(
            'availableClassrooms',
            'occupiedClassroomsData',
            'occupiedDetails',
            'days',
            'schedules',
            'dayId',
            'scheduleId',
            'date'
        ));
    }

    /**
     * Organizar horarios por día
     */
    private function organizeScheduleByDay($assignments)
    {
        // Mapeo de días en inglés a español
        $daysMap = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $organized = [];

        foreach ($days as $day) {
            $dayAssignments = $assignments->filter(function ($assignment) use ($day) {
                return trim($assignment->daySchedule->day->name) === $day;
            })->sortBy(function ($assignment) {
                return $assignment->daySchedule->schedule->start;
            })->values(); // Reindexar la colección

            if ($dayAssignments->count() > 0) {
                // Usar el nombre en español como clave
                $organized[$daysMap[$day]] = $dayAssignments;
            }
        }

        return $organized;
    }

    /**
     * Calcular estadísticas de asistencia
     */
    private function calculateAttendanceStatistics($records)
    {
        $total = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent = $records->where('status', 'absent')->count();
        $late = $records->where('status', 'late')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'present_percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
            'absent_percentage' => $total > 0 ? round(($absent / $total) * 100, 2) : 0,
            'late_percentage' => $total > 0 ? round(($late / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Generar PDF de horarios semanales
     */
    private function generateWeeklySchedulePDF($scheduleData, $academicManagement, $groupId)
    {
        $group = $groupId ? Group::find($groupId) : null;
        
        $pdf = Pdf::loadView('reports.pdf.weekly-schedules', [
            'scheduleData' => $scheduleData,
            'academicManagement' => $academicManagement,
            'group' => $group,
            'generatedAt' => now()->format('d/m/Y H:i')
        ])->setPaper('a4', 'landscape');

        $filename = 'Horario_Semanal_' . ($group ? $group->name . '_' : '') . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generar PDF de asistencia
     */
    private function generateAttendancePDF($attendanceRecords, $statistics, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('reports.pdf.attendance', [
            'attendanceRecords' => $attendanceRecords,
            'statistics' => $statistics,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()->format('d/m/Y H:i')
        ])->setPaper('a4', 'portrait');

        $filename = 'Reporte_Asistencia_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generar PDF de aulas disponibles
     */
    private function generateAvailableClassroomsPDF($availableClassrooms, $occupiedClassrooms, $occupiedDetails, $date)
    {
        $pdf = Pdf::loadView('reports.pdf.available-classrooms', [
            'availableClassrooms' => $availableClassrooms,
            'occupiedClassrooms' => $occupiedClassrooms,
            'occupiedDetails' => $occupiedDetails,
            'date' => $date,
            'generatedAt' => now()->format('d/m/Y H:i')
        ])->setPaper('a4', 'portrait');

        $filename = 'Aulas_Disponibles_' . $date . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Exportar horarios semanales a Excel (CSV)
     */
    public function exportWeeklySchedules(Request $request)
    {
        $academicManagementId = $request->input('academic_management_id');
        $groupId = $request->input('group_id');

        $academicManagement = $academicManagementId 
            ? AcademicManagement::find($academicManagementId)
            : AcademicManagement::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

        if (!$academicManagement) {
            return back()->with('error', 'No hay periodo académico activo.');
        }

        $query = Assignment::with([
            'userSubject.subject',
            'userSubject.user',
            'group',
            'daySchedule.day',
            'daySchedule.schedule',
            'classroom.infrastructure'
        ])->where('academic_management_id', $academicManagement->id);

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        $assignments = $query->get();
        $scheduleData = $this->organizeScheduleByDay($assignments);

        $filename = 'Horario_Semanal_' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($scheduleData, $academicManagement, $groupId) {
            $output = fopen('php://output', 'w');
            
            // BOM para UTF-8 en Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezado del reporte
            fputcsv($output, ['REPORTE DE HORARIOS SEMANALES']);
            fputcsv($output, ['Periodo Académico: ' . $academicManagement->name]);
            if ($groupId) {
                $group = Group::find($groupId);
                fputcsv($output, ['Grupo: ' . $group->name]);
            }
            fputcsv($output, ['Generado: ' . now()->format('d/m/Y H:i')]);
            fputcsv($output, []); // Línea vacía

            // Encabezados de columnas
            fputcsv($output, ['Día', 'Horario Inicio', 'Horario Fin', 'Materia', 'Código', 'Docente', 'Grupo', 'Aula', 'Infraestructura']);

            // Datos
            foreach ($scheduleData as $day => $assignments) {
                foreach ($assignments as $assignment) {
                    fputcsv($output, [
                        $day, // Ya viene en español desde organizeScheduleByDay
                        Carbon::parse($assignment->daySchedule->schedule->start)->format('H:i'),
                        Carbon::parse($assignment->daySchedule->schedule->end)->format('H:i'),
                        $assignment->userSubject->subject->name,
                        $assignment->userSubject->subject->code,
                        $assignment->userSubject->user->name,
                        $assignment->group->name,
                        $assignment->classroom->name,
                        $assignment->classroom->infrastructure->name
                    ]);
                }
            }

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /**
     * Exportar asistencia a Excel (CSV)
     */
    public function exportAttendance(Request $request)
    {
        $userId = $request->input('user_id');
        $groupId = $request->input('group_id');
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = AttendanceRecord::with([
            'assignment.userSubject.subject',
            'assignment.userSubject.user',
            'assignment.group',
            'assignment.daySchedule.day',
            'assignment.daySchedule.schedule',
            'user'
        ])->whereBetween('created_at', [$startDate, $endDate]);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($groupId) {
            $query->whereHas('assignment', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            });
        }

        $attendanceRecords = $query->orderBy('created_at', 'desc')->get();
        $statistics = $this->calculateAttendanceStatistics($attendanceRecords);

        $filename = 'Reporte_Asistencia_' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($attendanceRecords, $statistics, $startDate, $endDate) {
            $output = fopen('php://output', 'w');
            
            // BOM para UTF-8 en Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezado del reporte
            fputcsv($output, ['REPORTE DE ASISTENCIA DOCENTE']);
            fputcsv($output, ['Periodo: ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y')]);
            fputcsv($output, ['Generado: ' . now()->format('d/m/Y H:i')]);
            fputcsv($output, []);

            // Estadísticas
            fputcsv($output, ['ESTADÍSTICAS']);
            fputcsv($output, ['Total de Registros', $statistics['total']]);
            fputcsv($output, ['Presentes', $statistics['present'], $statistics['present_percentage'] . '%']);
            fputcsv($output, ['Ausentes', $statistics['absent'], $statistics['absent_percentage'] . '%']);
            fputcsv($output, ['Tardanzas', $statistics['late'], $statistics['late_percentage'] . '%']);
            fputcsv($output, []);

            // Encabezados de columnas
            fputcsv($output, ['Fecha', 'Docente', 'Materia', 'Código', 'Grupo', 'Día', 'Hora Inicio', 'Hora Fin', 'Estado', 'Hora Marcado']);

            // Datos
            foreach ($attendanceRecords as $record) {
                $status = match($record->status) {
                    'present' => 'Presente',
                    'late' => 'Tardanza',
                    default => 'Ausente'
                };

                fputcsv($output, [
                    Carbon::parse($record->created_at)->format('d/m/Y'),
                    $record->user->name,
                    $record->assignment->userSubject->subject->name,
                    $record->assignment->userSubject->subject->code,
                    $record->assignment->group->name,
                    __($record->assignment->daySchedule->day->name),
                    Carbon::parse($record->assignment->daySchedule->schedule->start)->format('H:i'),
                    Carbon::parse($record->assignment->daySchedule->schedule->end)->format('H:i'),
                    $status,
                    $record->scan_time ? Carbon::parse($record->scan_time)->format('H:i:s') : '-'
                ]);
            }

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /**
     * Exportar aulas disponibles a Excel (CSV)
     */
    public function exportAvailableClassrooms(Request $request)
    {
        $dayId = $request->input('day_id');
        $scheduleId = $request->input('schedule_id');
        $date = $request->input('date', now()->format('Y-m-d'));

        $academicManagement = AcademicManagement::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        if (!$academicManagement) {
            return back()->with('error', 'No hay periodo académico activo para la fecha seleccionada.');
        }

        $allClassrooms = Classroom::with('infrastructure')->where('is_active', true)->get();

        $occupiedQuery = Assignment::with([
            'classroom',
            'userSubject.subject',
            'userSubject.user',
            'group',
            'daySchedule.day',
            'daySchedule.schedule'
        ])->where('academic_management_id', $academicManagement->id);

        if ($dayId) {
            $occupiedQuery->whereHas('daySchedule', function ($q) use ($dayId) {
                $q->where('day_id', $dayId);
            });
        }

        if ($scheduleId) {
            $occupiedQuery->whereHas('daySchedule', function ($q) use ($scheduleId) {
                $q->where('schedule_id', $scheduleId);
            });
        }

        $occupiedClassrooms = $occupiedQuery->get()->pluck('classroom_id')->toArray();
        $availableClassrooms = $allClassrooms->whereNotIn('id', $occupiedClassrooms);
        $occupiedClassroomsData = $allClassrooms->whereIn('id', $occupiedClassrooms);

        $occupiedDetails = [];
        foreach ($occupiedClassroomsData as $classroom) {
            $assignment = Assignment::with([
                'userSubject.subject',
                'userSubject.user',
                'group',
                'daySchedule.day',
                'daySchedule.schedule'
            ])->where('classroom_id', $classroom->id)
              ->where('academic_management_id', $academicManagement->id);

            if ($dayId) {
                $assignment->whereHas('daySchedule', function ($q) use ($dayId) {
                    $q->where('day_id', $dayId);
                });
            }

            if ($scheduleId) {
                $assignment->whereHas('daySchedule', function ($q) use ($scheduleId) {
                    $q->where('schedule_id', $scheduleId);
                });
            }

            $occupiedDetails[$classroom->id] = $assignment->first();
        }

        $filename = 'Aulas_Disponibles_' . $date . '.csv';

        return response()->streamDownload(function () use ($availableClassrooms, $occupiedClassroomsData, $occupiedDetails, $date) {
            $output = fopen('php://output', 'w');
            
            // BOM para UTF-8 en Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezado del reporte
            fputcsv($output, ['REPORTE DE DISPONIBILIDAD DE AULAS']);
            fputcsv($output, ['Fecha: ' . Carbon::parse($date)->format('d/m/Y')]);
            fputcsv($output, ['Generado: ' . now()->format('d/m/Y H:i')]);
            fputcsv($output, []);

            // Resumen
            fputcsv($output, ['RESUMEN']);
            fputcsv($output, ['Aulas Disponibles', $availableClassrooms->count()]);
            fputcsv($output, ['Aulas Ocupadas', $occupiedClassroomsData->count()]);
            fputcsv($output, []);

            // Aulas Disponibles
            fputcsv($output, ['AULAS DISPONIBLES']);
            fputcsv($output, ['Aula', 'Infraestructura', 'Capacidad', 'Tipo']);
            foreach ($availableClassrooms as $classroom) {
                fputcsv($output, [
                    $classroom->name,
                    $classroom->infrastructure->name,
                    $classroom->capacity,
                    ucfirst($classroom->type)
                ]);
            }
            fputcsv($output, []);

            // Aulas Ocupadas
            fputcsv($output, ['AULAS OCUPADAS']);
            fputcsv($output, ['Aula', 'Infraestructura', 'Capacidad', 'Materia', 'Docente', 'Grupo', 'Horario']);
            foreach ($occupiedClassroomsData as $classroom) {
                $assignment = $occupiedDetails[$classroom->id] ?? null;
                if ($assignment) {
                    fputcsv($output, [
                        $classroom->name,
                        $classroom->infrastructure->name,
                        $classroom->capacity,
                        $assignment->userSubject->subject->name,
                        $assignment->userSubject->user->name,
                        $assignment->group->name,
                        Carbon::parse($assignment->daySchedule->schedule->start)->format('H:i') . ' - ' . 
                        Carbon::parse($assignment->daySchedule->schedule->end)->format('H:i')
                    ]);
                }
            }

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
