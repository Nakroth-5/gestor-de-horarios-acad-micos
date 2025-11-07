<?php

namespace App\Livewire\AcademicLogistics;

use App\Livewire\AcademicLogistics\Forms\ManualAssignmentForm;
use App\Models\AcademicManagement;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\DaySchedule;
use App\Models\UserSubject;
use App\Models\Group;
use App\Models\User;
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

    public function render(): View
    {
        // Obtener asignaciones agrupadas
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
                        });
                });
            })
            ->get();

        // Agrupar por user_subject_id y group_id (cada combinación materia-docente-grupo es única)
        return $assignments->groupBy(function ($assignment) {
            return $assignment->user_subject_id . '-' . $assignment->group_id;
        })
            ->map(function ($group) {
                $first = $group->first(); // CORRECCIÓN: usar $group en lugar de $assignment
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
            })
            ->values();
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
        $this->show = true;
    }

    public function edit($id): void
    {
        $assignment = Assignment::with(['userSubject', 'group', 'classroom', 'daySchedule', 'academicManagement'])
            ->findOrFail($id);

        $this->editing = $id;
        $this->form->setAssignment($assignment);
        $this->show = true;
    }

    public function closeModal(): void
    {
        $this->show = false;
        $this->form->reset();
        $this->editing = null;
        $this->dispatch('modal-closed');
    }

    public function save(): void
    {
        $this->form->validate();

        try {
            $this->validateConflicts();

            $data = [
                'user_subject_id' => $this->form->user_subject_id,
                'group_id' => $this->form->group_id,
                'classroom_id' => $this->form->classroom_id,
                'day_schedule_id' => $this->form->day_schedule_id,
                'academic_management_id' => $this->form->academic_id,
            ];

            // Llenar subject_id automáticamente desde user_subject
            $userSubject = UserSubject::find($this->form->user_subject_id);
            if ($userSubject) {
                $data['subject_id'] = $userSubject->subject_id;
            }

            if ($this->editing) {
                $assignment = Assignment::find($this->editing);
                $assignment->update($data);
                session()->flash('message', 'Asignación actualizada correctamente.');
            } else {
                Assignment::create($data);
                session()->flash('message', 'Asignación creada correctamente.');
            }

            $this->closeModal();
            $this->getRelations(); // Actualizar relaciones

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    private function validateConflicts(): void
    {
        // Validar conflicto de aula
        $classroomConflict = Assignment::where('classroom_id', $this->form->classroom_id)
            ->where('day_schedule_id', $this->form->day_schedule_id)
            ->where('academic_management_id', $this->form->academic_id)
            ->when($this->editing, function ($query) {
                $query->where('id', '!=', $this->editing);
            })
            ->exists();

        if ($classroomConflict) {
            throw new \Exception('Esta aula ya está ocupada en ese horario para el periodo académico seleccionado.');
        }

        // Validar conflicto de materia-grupo en el mismo periodo
        $userSubject = UserSubject::find($this->form->user_subject_id);

        if ($userSubject) {
            $subjectGroupConflict = Assignment::where('subject_id', $userSubject->subject_id)
                ->where('group_id', $this->form->group_id)
                ->where('academic_management_id', $this->form->academic_id)
                ->when($this->editing, function ($query) {
                    $query->where('id', '!=', $this->editing);
                })
                ->exists();

            if ($subjectGroupConflict) {
                throw new \Exception('Esta materia ya está asignada a este grupo en el periodo académico seleccionado.');
            }
        }
    }

    public function delete($id): void
    {
        try {
            Assignment::findOrFail($id)->delete();
            session()->flash('message', 'Asignación eliminada correctamente.');
            $this->getRelations(); // Actualizar relaciones
        } catch (\Exception $e) {
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
}
