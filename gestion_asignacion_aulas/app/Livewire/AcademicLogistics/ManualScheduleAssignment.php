<?php

namespace App\Livewire\AcademicLogistics;

use App\Livewire\AcademicLogistics\Forms\ManualAssignmentForm;
use App\Models\AcademicManagement;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\DaySchedule;
use App\Models\UserSubject;
use App\Models\Group;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ManualScheduleAssignment extends Component
{
    use WithPagination;

    protected $listeners = ['refreshComponent' => 'render'];
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $editing = null;
    public bool $show = false;

    public ManualAssignmentForm $form;

    public $allUserSubject = [];
    public $allDaySchedule = [];
    public $allClassroom = [];
    public $allAcademic = [];
    public $allGroups = [];

    public $schedules = [
        ['day_schedule_id' => null, 'classroom_id' => null],
        ['day_schedule_id' => null, 'classroom_id' => null],
        ['day_schedule_id' => null, 'classroom_id' => null],
        ['day_schedule_id' => null, 'classroom_id' => null],
        ['day_schedule_id' => null, 'classroom_id' => null],
        ['day_schedule_id' => null, 'classroom_id' => null]
    ];

    // Mapeo de día en español a DaySchedule que corresponden a ese día
    private $daySchedulesByDayName = [];

    public function render(): View
    {
        $assignments = $this->getGroupedAssignments();

        return view('livewire.academic-logistics.manual-schedule-assigment.manual-schedule-assignment',
            compact(
                'assignments',
                'this'
            ));
    }

    private function getGroupedAssignments(): Collection
    {
        $assignments = Assignment::query()
            ->with([
                'userSubject.user',
                'userSubject.subject',
                'group',
                'daySchedule.day',
                'daySchedule.schedule',
                'classroom.module',
                'academicManagement'
            ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('userSubject.subject', function ($subQuery) {
                        $subQuery->where('code', 'like', '%' . $this->search . '%')
                            ->orWhere('name', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('group', function ($groupQuery) {
                            $groupQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('userSubject.user', function ($userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('last_name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('classroom', function ($classroomQuery) {
                            $classroomQuery->where('number', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->get()
            ->groupBy(function ($assignment) {
                return $assignment->user_subject_id . '-' . $assignment->group_id . '-' . $assignment->academic_management_id;
            });

        return $assignments->map(function ($group) {
            $first = $group->first();
            $userSubject = $first->userSubject;

            // Ordenar por día de la semana
            $schedules = $group->sortBy(function ($assignment) {
                $dayOrder = [
                    'Lunes' => 1,
                    'Martes' => 2,
                    'Miércoles' => 3,
                    'Jueves' => 4,
                    'Viernes' => 5,
                    'Sábado' => 6,
                    'Domingo' => 7
                ];
                return $dayOrder[$assignment->daySchedule->day->name] ?? 8;
            });

            return [
                'subject_code' => $userSubject->subject->code,
                'subject_group' => $first->group->name,
                'subject_name' => $userSubject->subject->name,
                'teacher_name' => $userSubject->user->name . ' ' . $userSubject->user->last_name,
                'schedules' => $schedules,
                'ids' => $group->pluck('id')->toArray()
            ];
        })->values();
    }

    public function getRelations(): void
    {
        // Obtener todas las combinaciones de docente-materia
        $this->allUserSubject = UserSubject::with(['subject', 'user'])
            ->whereHas('subject', function ($q) {
                $q->where('is_active', true);
            })
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        $this->allDaySchedule = DaySchedule::with(['day', 'schedule'])->get();
        $this->allClassroom = Classroom::with('module')->where('is_active', true)->get();
        $this->allAcademic = AcademicManagement::all();
        $this->allGroups = Group::where('is_active', true)->get();
    }

    public function mount(): void
    {
        $this->getRelations();
    }

    public function openCreateModal(): void
    {
        $this->editing = null;
        $this->form->reset();
        $this->resetSchedules();
        $this->show = true;
    }

    public function edit($id): void
    {
        $assignment = Assignment::with(['userSubject', 'group', 'classroom', 'daySchedule', 'academicManagement'])
            ->findOrFail($id);

        $this->editing = $id;
        $this->form->setAssignment($assignment);

        // Cargar todos los horarios de esta asignatura
        $allAssignments = Assignment::where('user_subject_id', $assignment->user_subject_id)
            ->where('group_id', $assignment->group_id)
            ->where('academic_management_id', $assignment->academic_management_id)
            ->get();

        $this->resetSchedules();

        // Llenar los horarios existentes
        foreach ($allAssignments as $index => $assign) {
            $this->schedules[$index] = [
                'day_schedule_id' => $assign->day_schedule_id,
                'classroom_id' => $assign->classroom_id
            ];
        }

        // Si hay menos de 3 horarios, asegurar que tengamos 3 slots
        while (count($this->schedules) < 3) {
            $this->schedules[] = ['day_schedule_id' => null, 'classroom_id' => null];
        }

        $this->show = true;
    }

    public function closeModal(): void
    {
        $this->show = false;
        $this->form->reset();
        $this->resetSchedules();
        $this->editing = null;
        $this->dispatch('modal-closed');
    }

    public function save(): void
    {
        $this->validate([
            'form.user_subject_id' => 'required|exists:user_subjects,id',
            'form.group_id' => 'required|exists:groups,id',
            'form.academic_id' => 'nullable|exists:academic_management,id',
            'schedules.*.day_schedule_id' => 'nullable|exists:day_schedules,id',
            'schedules.*.classroom_id' => 'required_with:schedules.*.day_schedule_id|exists:classrooms,id',
        ], [
            'schedules.*.day_schedule_id.exists' => 'Uno de los horarios seleccionados no es válido.',
            'schedules.*.classroom_id.required_with' => 'Debe seleccionar un aula para el horario.',
            'schedules.*.classroom_id.exists' => 'Uno de los aulas seleccionadas no es válida.',
        ]);

        try {
            // Validar que al menos un horario esté seleccionado
            $selectedSchedules = array_filter($this->schedules, function($schedule) {
                return !empty($schedule['day_schedule_id']) && !empty($schedule['classroom_id']);
            });

            if (empty($selectedSchedules)) {
                throw new \Exception('Debe seleccionar al menos un horario con su aula correspondiente.');
            }

            $this->validateConflicts();

            $userSubject = UserSubject::find($this->form->user_subject_id);

            if ($this->editing) {
                // Modo edición: actualizar solo los cambios
                $this->updateExistingAssignments($selectedSchedules, $userSubject);
            } else {
                // Modo creación: crear nuevas asignaciones
                $this->createNewAssignments($selectedSchedules, $userSubject);
            }

            session()->flash('message',
                $this->editing ? 'Asignación actualizada correctamente.' : 'Asignación creada correctamente.'
            );

            $this->closeModal();
            $this->getRelations();

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    private function updateExistingAssignments(array $selectedSchedules, UserSubject $userSubject): void
    {
        // Obtener asignaciones existentes
        $existingAssignments = Assignment::where('user_subject_id', $this->form->user_subject_id)
            ->where('group_id', $this->form->group_id)
            ->where('academic_management_id', $this->form->academic_id)
            ->get();

        $existingScheduleIds = [];
        $processedSchedules = [];

        // Actualizar o crear asignaciones
        foreach ($selectedSchedules as $schedule) {
            if ($schedule['day_schedule_id'] && $schedule['classroom_id']) {
                $scheduleKey = $schedule['day_schedule_id'] . '-' . $schedule['classroom_id'];

                // Buscar si ya existe esta asignación
                $existingAssignment = $existingAssignments->first(function ($assignment) use ($schedule) {
                    return $assignment->day_schedule_id == $schedule['day_schedule_id']
                        && $assignment->classroom_id == $schedule['classroom_id'];
                });

                if ($existingAssignment) {
                    // Ya existe, mantenerla
                    $existingScheduleIds[] = $existingAssignment->id;
                    $processedSchedules[] = $existingAssignment;
                } else {
                    // Crear nueva asignación
                    $newAssignment = Assignment::create([
                        'user_subject_id' => $this->form->user_subject_id,
                        'subject_id' => $userSubject->subject_id,
                        'group_id' => $this->form->group_id,
                        'classroom_id' => $schedule['classroom_id'],
                        'day_schedule_id' => $schedule['day_schedule_id'],
                        'academic_management_id' => $this->form->academic_id,
                    ]);
                    $existingScheduleIds[] = $newAssignment->id;
                    $processedSchedules[] = $newAssignment;
                }
            }
        }

        // Eliminar asignaciones que ya no están en los horarios seleccionados
        $assignmentsToDelete = $existingAssignments->whereNotIn('id', $existingScheduleIds);
        foreach ($assignmentsToDelete as $assignment) {
            $assignment->delete();
        }
    }

    private function createNewAssignments(array $selectedSchedules, UserSubject $userSubject): void
    {
        foreach ($selectedSchedules as $schedule) {
            if ($schedule['day_schedule_id'] && $schedule['classroom_id']) {
                Assignment::create([
                    'user_subject_id' => $this->form->user_subject_id,
                    'subject_id' => $userSubject->subject_id,
                    'group_id' => $this->form->group_id,
                    'classroom_id' => $schedule['classroom_id'],
                    'day_schedule_id' => $schedule['day_schedule_id'],
                    'academic_management_id' => $this->form->academic_id,
                ]);
            }
        }
    }
    private function validateConflicts(): void
    {
        $selectedSchedules = array_filter($this->schedulesByDay, function ($schedule) {
            return !empty($schedule['day_schedule_id']) && !empty($schedule['classroom_id']);
        });

        // Obtener IDs de asignaciones existentes (en modo edición)
        $existingAssignmentIds = [];
        if ($this->editing) {
            $existingAssignments = Assignment::where('user_subject_id', $this->form->user_subject_id)
                ->where('group_id', $this->form->group_id)
                ->where('academic_management_id', $this->form->academic_id)
                ->get();
            $existingAssignmentIds = $existingAssignments->pluck('id')->toArray();
        }

        foreach ($selectedSchedules as $dayName => $schedule) {
            // Validar conflicto de aula
            $classroomConflictQuery = Assignment::where('classroom_id', $schedule['classroom_id'])
                ->where('day_schedule_id', $schedule['day_schedule_id'])
                ->where('academic_management_id', $this->form->academic_id);

            if ($this->editing && !empty($existingAssignmentIds)) {
                $classroomConflictQuery->whereNotIn('id', $existingAssignmentIds);
            }

            if ($classroomConflictQuery->exists()) {
                $daySchedule = DaySchedule::with(['day', 'schedule'])->find($schedule['day_schedule_id']);
                $classroom = Classroom::find($schedule['classroom_id']);
                throw new \Exception(
                    "El aula {$classroom->number} ya está ocupada el {$daySchedule->day->name} de " .
                    date('H:i', strtotime($daySchedule->schedule->start)) . " a " .
                    date('H:i', strtotime($daySchedule->schedule->end)) .
                    " para el periodo académico seleccionado."
                );
            }
        }

        // Validar conflicto de materia-grupo
        $userSubject = UserSubject::find($this->form->user_subject_id);
        if ($userSubject) {
            $subjectGroupConflictQuery = Assignment::where('subject_id', $userSubject->subject_id)
                ->where('group_id', $this->form->group_id)
                ->where('academic_management_id', $this->form->academic_id);

            if ($this->editing) {
                $subjectGroupConflictQuery->where('user_subject_id', '!=', $this->form->user_subject_id);
            }

            if ($subjectGroupConflictQuery->exists()) {
                throw new \Exception('Esta materia ya está asignada a este grupo en el periodo académico seleccionado.');
            }
        }
    }

    public function delete($id): void
    {
        try {
            $assignment = Assignment::findOrFail($id);

            Assignment::where('user_subject_id', $assignment->user_subject_id)
                ->where('group_id', $assignment->group_id)
                ->where('academic_management_id', $assignment->academic_management_id)
                ->delete();

            session()->flash('message', 'Asignación eliminada correctamente.');
            $this->getRelations();
        } catch (Exception $e) {
            session()->flash('error', 'Error al eliminar la asignación: ' . $e->getMessage());
        }
    }

    private function resetSchedules(): void
    {
        $this->schedulesByDay = [
            'Lunes' => ['day_schedule_id' => null, 'classroom_id' => null],
            'Martes' => ['day_schedule_id' => null, 'classroom_id' => null],
            'Miércoles' => ['day_schedule_id' => null, 'classroom_id' => null],
            'Jueves' => ['day_schedule_id' => null, 'classroom_id' => null],
            'Viernes' => ['day_schedule_id' => null, 'classroom_id' => null],
            'Sábado' => ['day_schedule_id' => null, 'classroom_id' => null],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    // Obtener horarios disponibles para un día específico
    public function getSchedulesForDay(string $dayName): array
    {
        // Si no existe el día en el mapeo, retornar array vacío
        if (!isset($this->daySchedulesByDayName[$dayName])) {
            return [];
        }

        $schedules = [];
        foreach ($this->daySchedulesByDayName[$dayName] as $daySchedule) {
            $schedules[] = $daySchedule;
        }

        return $schedules;
    }
}

