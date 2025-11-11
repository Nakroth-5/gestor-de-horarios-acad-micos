<?php

namespace App\Livewire\AcademicProcesses;

use App\Livewire\AcademicProcesses\Forms\TeacherSubjectForm;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubject;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TeacherSubjectManager extends Component
{
    use WithPagination;

    public $search = '';
    public $show = false;
    public $editing = null;
    protected $pagination_theme = 'tailwind';

    public TeacherSubjectForm $form;

    protected $listeners = ['refreshComponent' => 'render'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        // Obtener el ID del rol "Docente"
        $docenteRoleId = DB::table('roles')->where('name', 'Docente')->value('id');

        // Query para usuarios con rol Docente
        $query = User::query()
            ->where('is_active', true)
            ->whereHas('roles', function ($q) use ($docenteRoleId) {
                $q->where('roles.id', $docenteRoleId);
            });

        // Búsqueda
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'ILIKE', $searchTerm)
                    ->orWhere('last_name', 'ILIKE', $searchTerm)
                    ->orWhere('email', 'ILIKE', $searchTerm)
                    ->orWhere('document_number', 'ILIKE', $searchTerm);
            });
        }

        $teachers = $query->orderBy('name')->paginate(10);

        // Cargar todas las materias activas para el select
        $allSubjects = Subject::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Cargar todas las carreras
        $allCareers = \App\Models\UniversityCareer::orderBy('name')->get();

        return view('livewire.academic-processes.teacher-subject.teacher-subject-manager', compact('teachers', 'allSubjects', 'allCareers'));
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $this->resetErrorBag();

        $user = User::find($id);

        if (!$user) {
            session()->flash('error', 'Docente no encontrado.');
            return;
        }

        // Verificar que el usuario tenga rol Docente
        if (!$user->hasRole('Docente')) {
            session()->flash('error', 'El usuario seleccionado no es docente.');
            return;
        }

        $this->editing = $user->id;
        $this->form->set($user);
        $this->show = true;
    }

    public function save(): void
    {
        $this->form->validate();

        try {
            $data = $this->form->getData();
            $userId = $data['user_id'];
            $subjectIds = $data['subject_ids'];
            $subjectCareers = $data['subject_careers'];

            // Verificar que el usuario existe y es docente
            $user = User::find($userId);
            if (!$user || !$user->hasRole('Docente')) {
                session()->flash('error', 'Docente no encontrado o inválido.');
                return;
            }

            // Filtrar y obtener solo valores únicos y válidos
            $subjectIds = array_unique(array_filter($subjectIds, function($id) {
                return !empty($id) && is_numeric($id);
            }));

            if (empty($subjectIds)) {
                session()->flash('error', 'Debe seleccionar al menos una materia.');
                return;
            }

            DB::beginTransaction();

            // Obtener todas las combinaciones actuales
            $existingAssignments = UserSubject::where('user_id', $userId)->get();
            
            // Crear array de las nuevas combinaciones (subject_id + career_id)
            $newCombinations = [];
            foreach ($subjectIds as $subjectId) {
                $careerId = $subjectCareers[$subjectId] ?? null;
                $newCombinations[] = [
                    'subject_id' => $subjectId,
                    'career_id' => $careerId,
                ];
            }

            // Eliminar las asignaciones que ya no están en la nueva lista
            foreach ($existingAssignments as $existing) {
                $existsInNew = false;
                foreach ($newCombinations as $new) {
                    if ($existing->subject_id == $new['subject_id'] && 
                        $existing->university_career_id == $new['career_id']) {
                        $existsInNew = true;
                        break;
                    }
                }
                if (!$existsInNew) {
                    $existing->delete();
                }
            }

            // Crear las nuevas asignaciones que no existen
            foreach ($newCombinations as $new) {
                $exists = $existingAssignments->first(function($existing) use ($new) {
                    return $existing->subject_id == $new['subject_id'] && 
                           $existing->university_career_id == $new['career_id'];
                });

                if (!$exists) {
                    UserSubject::create([
                        'user_id' => $userId,
                        'subject_id' => $new['subject_id'],
                        'university_career_id' => $new['career_id'],
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Materias asignadas correctamente al docente.');
            $this->closeModal();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al asignar materias al docente: ' . $e->getMessage());
            session()->flash('error', 'Error al asignar materias: ' . $e->getMessage());
        }
    }

    public function closeModal(): void
    {
        $this->show = false;
        $this->form->reset();
        $this->dispatch('modal-closed');
    }

    public function mount(): void
    {
        $this->form->reset();
    }
}
