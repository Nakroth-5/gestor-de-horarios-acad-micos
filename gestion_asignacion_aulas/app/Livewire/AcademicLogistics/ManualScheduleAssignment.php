<?php

namespace App\Livewire\AcademicLogistics;

use App\Livewire\AcademicLogistics\Forms\ManualAssignmentForm;
use App\Models\AcademicManagement;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\DaySchedule;
use App\Models\UniversityCareer;
use App\Models\UserSubject;
use App\Models\Group;
use App\Models\User;
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

    public $existingAssignments = [];

    // Array para todos los días de la semana
    public $schedules = [];

    // Días de la semana disponibles
    public $daysOfWeek = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
    ];

    public function render(): View
    {
        $assignments = $this->getGroupedAssignments();

        return view('livewire.academic-logistics.manual-schedule-assigment.manual-schedule-assignment',
            compact('assignments'));
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

            $schedules = $group->sortBy(function ($assignment) {
                $dayOrder = [
                    'Monday' => 1,
                    'Tuesday' => 2,
                    'Wednesday' => 3,
                    'Thursday' => 4,
                    'Friday' => 5,
                    'Saturday' => 6,
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

        // Instanciar valores por defecto de los horarios por día
        $this->initializeSchedules();
    }

    private function initializeSchedules(): void
    {
        $this->schedules = [];
        foreach ($this->daysOfWeek as $day) {
            $this->schedules[$day] = [
                'day_schedule_id' => null,
                'classroom_id' => null
            ];
        }
    }

    public function openCreateModal(): void
    {
        $this->editing = null;
        $this->form->reset();
        $this->initializeSchedules();
        $this->existingAssignments = [];
        $this->show = true;
    }

    public function edit($id): void
    {
        $assignment = Assignment::with(['userSubject', 'group', 'classroom', 'daySchedule', 'academicManagement'])
            ->findOrFail($id);

        $this->editing = $id;
        $this->form->setAssignment($assignment);

        $allAssignments = Assignment::where('user_subject_id', $assignment->user_subject_id)
            ->where('group_id', $assignment->group_id)
            ->where('academic_management_id', $assignment->academic_management_id)
            ->with(['daySchedule.day'])
            ->get();

        $this->initializeSchedules();
        $this->existingAssignments = [];

        foreach ($allAssignments as $assign) {
            // Normalizar el nombre del día para que coincida con las claves en $this->daysOfWeek
            $rawDayName = $assign->daySchedule->day->name ?? '';
            $dayName = ucfirst(strtolower(trim($rawDayName)));

            if (in_array($dayName, $this->daysOfWeek, true)) {
                // Asegurar que los IDs estén en formato entero (no strings) para que los selects los reconozcan
                $this->schedules[$dayName] = [
                    'day_schedule_id' => $assign->day_schedule_id ? (int) $assign->day_schedule_id : null,
                    'classroom_id' => $assign->classroom_id ? (int) $assign->classroom_id : null
                ];
                $this->existingAssignments[$dayName] = $assign->id;
            }
        }
        $this->show = true;
    }

    public function closeModal(): void
    {
        $this->show = false;
        $this->form->reset();
        $this->initializeSchedules();
        $this->editing = null;
        $this->dispatch('modal-closed');
    }

    // Método para obtener los horarios filtrados por día
    public function getSchedulesForDay($dayName): Collection
    {
        return $this->allDaySchedule->filter(function ($daySchedule) use ($dayName) {
            if (!$daySchedule || !$daySchedule->day) {
                return false;
            }
            $scheduleDayName = trim($daySchedule->day->name);
            return strcasecmp($scheduleDayName, $dayName) === 0;
        });
    }

    // Método para obtener el nombre del día en español
    public function getDayNameInSpanish($dayName): string
    {
        $translations = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado'
        ];
        return $translations[$dayName] ?? $dayName;
    }

    public function save(): void
    {

        $this->validate([
            'form.user_subject_id' => 'required|exists:user_subjects,id',
            'form.group_id' => 'required|exists:groups,id',
            'form.academic_id' => 'nullable|exists:academic_management,id',
        ], [
            'form.user_subject_id.required' => 'Debe seleccionar un docente y materia.',
            'form.group_id.required' => 'Debe seleccionar un grupo.',
        ]);

        try {
            // Filtrar solo los horarios completos (con día-horario Y aula)
            $validSchedules = $this->getValidSchedules();

            if (empty($validSchedules)) {
                throw new \Exception("Debe seleccionar al menos un horario completo (día, horario y aula).");
            }

            $this->validateConflicts($validSchedules);

            $userSubject = UserSubject::find($this->form->user_subject_id);

            if ($this->editing) {
                $this->updateExistingAssignment($userSubject, $validSchedules);
            } else {
                $this->createNewAssignment($userSubject, $validSchedules);
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

    /**
     * Obtiene solo los horarios válidos (con día-horario Y aula seleccionados)
     */
    private function getValidSchedules(): array
    {
        $validSchedules = [];

        foreach ($this->schedules as $day => $schedule) {
            // Solo agregar si AMBOS campos están seleccionados
            if (!empty($schedule['day_schedule_id']) && !empty($schedule['classroom_id'])) {
                $validSchedules[$day] = $schedule;
            }
        }

        return $validSchedules;
    }

    /**
     * @throws Exception
     */
    private function validateConflicts(array $validSchedules): void
    {
        foreach ($validSchedules as $day => $schedule) {
            $query = Assignment::where('classroom_id', $schedule['classroom_id'])
                ->where('day_schedule_id', $schedule['day_schedule_id'])
                ->where('academic_management_id', $this->form->academic_id);

            // En modo edición, excluir los assignments existentes de esta misma asignatura
            if ($this->editing) {
                $existingAssignmentIds = Assignment::where('user_subject_id', $this->form->user_subject_id)
                    ->where('group_id', $this->form->group_id)
                    ->where('academic_management_id', $this->form->academic_id)
                    ->pluck('id')
                    ->toArray();

                $query->whereNotIn('id', $existingAssignmentIds);
            }

            $classroomConflict = $query->exists();

            if ($classroomConflict) {
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

        // Validar conflicto de materia-grupo en el mismo periodo (solo para creación)
        if (!$this->editing) {
            $userSubject = UserSubject::find($this->form->user_subject_id);

            if ($userSubject) {
                $subjectGroupConflict = Assignment::where('subject_id', $userSubject->subject_id)
                    ->where('group_id', $this->form->group_id)
                    ->where('academic_management_id', $this->form->academic_id)
                    ->exists();

                if ($subjectGroupConflict) {
                    throw new \Exception('Esta materia ya está asignada a este grupo en el periodo académico seleccionado.');
                }
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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    private function updateExistingAssignment($userSubject, array $validSchedules): void
    {
        // Obtener assignments existentes para comparar
        $existingAssignments = Assignment::where('user_subject_id', $this->form->user_subject_id)
            ->where('group_id', $this->form->group_id)
            ->where('academic_management_id', $this->form->academic_id)
            ->where('university_career_id', $this->form->university_id)
            ->with('daySchedule.day')
            ->get()
            ->keyBy(function ($item) {
                return $item->daySchedule->day->name;
            });

        $processedDays = [];

        foreach ($validSchedules as $day => $schedule) {
            $processedDays[] = $day;

            if (isset($existingAssignments[$day])) {
                // Actualizar si cambió el aula o el horario
                $existingAssignment = $existingAssignments[$day];
                if ($existingAssignment->classroom_id != $schedule['classroom_id'] ||
                    $existingAssignment->day_schedule_id != $schedule['day_schedule_id']) {
                    $existingAssignment->update([
                        'classroom_id' => $schedule['classroom_id'],
                        'day_schedule_id' => $schedule['day_schedule_id'],
                        'subject_id' => $userSubject->subject_id,
                    ]);
                }
            } else {
                // Crear nuevo assignment
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

        // Eliminar assignments que ya no están en los días seleccionados
        $schedulesToDelete = $existingAssignments->filter(function ($assignment, $day) use ($processedDays) {
            return !in_array($day, $processedDays);
        });

        foreach ($schedulesToDelete as $assignmentToDelete) {
            $assignmentToDelete->delete();
        }
    }

    private function createNewAssignment($userSubject, array $validSchedules): void
    {
       // dd($this->all());
        foreach ($validSchedules as $day => $schedule) {
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
