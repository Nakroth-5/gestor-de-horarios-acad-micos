<?php

namespace App\Livewire\AcademicProcesses;

use App\Models\Assignment;
use App\Models\AcademicManagement;
use App\Models\UserSubject;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class TeacherScheduleView extends Component
{
    public $selectedPeriod;
    public $academicPeriods;
    public $viewMode = 'schedule'; // schedule, subjects, summary

    public function mount()
    {
        $this->academicPeriods = AcademicManagement::orderBy('start_date', 'desc')->get();
        $this->selectedPeriod = $this->academicPeriods->first()->id ?? null;
    }

    public function render()
    {
        $userId = Auth::id();

        // Obtener los user_subjects del docente
        $userSubjects = UserSubject::where('user_id', $userId)
            ->with(['subject', 'universityCareer'])
            ->get();

        // Obtener las asignaciones (horarios) del docente
        $assignments = Assignment::with(['daySchedule.day', 'daySchedule.schedule', 'classroom', 'group.academicManagement', 'subject', 'userSubject.universityCareer'])
            ->whereIn('user_subject_id', $userSubjects->pluck('id'))
            ->when($this->selectedPeriod, function($query) {
                $query->where('academic_management_id', $this->selectedPeriod);
            })
            ->orderBy('day_schedule_id')
            ->get();

        // Crear una colección con información de materias, grupos y carreras desde assignments
        // Agrupar por combinación única de materia + grupo para mostrar todos los grupos
        $subjectsWithGroups = $assignments->map(function($assignment) {
            return [
                'subject' => $assignment->subject,
                'group' => $assignment->group,
                'user_subject_id' => $assignment->user_subject_id,
                'career' => $assignment->userSubject->universityCareer ?? null,
                'unique_key' => $assignment->subject_id . '_' . $assignment->group_id,
            ];
        })->unique('unique_key')->values();

        // Organizar horario por días
        $scheduleByDay = $this->organizeScheduleByDay($assignments);

        // Estadísticas
        $stats = [
            'total_subjects' => $assignments->unique('subject_id')->count(),
            'total_groups' => $assignments->unique('group_id')->count(),
            'total_hours' => $assignments->count(),
            'classrooms' => $assignments->unique('classroom_id')->count(),
        ];

        return view('livewire.academic-processes.teacher-schedule-view', [
            'userSubjects' => $subjectsWithGroups,
            'assignments' => $assignments,
            'scheduleByDay' => $scheduleByDay,
            'stats' => $stats,
        ]);
    }

    private function organizeScheduleByDay($assignments)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $schedule = [];

        foreach ($days as $day) {
            $schedule[$day] = $assignments->filter(function($assignment) use ($day) {
                return $assignment->daySchedule 
                    && $assignment->daySchedule->day 
                    && trim($assignment->daySchedule->day->name) === $day;
            })->sortBy(function($assignment) {
                return $assignment->daySchedule->schedule->start ?? '';
            })->values();
        }

        return $schedule;
    }

    public function changeViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function updatedSelectedPeriod()
    {
        $this->render();
    }
}
