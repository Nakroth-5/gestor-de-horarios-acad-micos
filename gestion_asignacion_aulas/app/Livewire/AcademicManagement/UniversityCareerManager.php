<?php

namespace App\Livewire\AcademicManagement;

use App\Models\UniversityCareer;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UniversityCareerManager extends Component
{
    use WithPagination;

    public $careerId;
    public $name;
    public $code;
    public $study_level;
    public $duration_years;
    public $faculty;
    public $language = 'Español';
    public $search = '';
    public $isOpen = false;
    
    // Para gestión de materias
    public $isSubjectsModalOpen = false;
    public $selectedCareer;
    public $selectedSubjects = [];
    public $subjectSemesters = [];
    public $subjectIsRequired = [];

    protected $rules = [
        'name' => 'required|string|max:100',
        'code' => 'required|string|max:20|unique:university_careers,code',
        'study_level' => 'required|string|max:50',
        'duration_years' => 'required|integer|min:1|max:10',
        'faculty' => 'required|string|max:100',
        'language' => 'required|string|max:50',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'code.required' => 'El código es obligatorio.',
        'code.unique' => 'Este código ya está en uso.',
        'study_level.required' => 'El nivel de estudio es obligatorio.',
        'duration_years.required' => 'La duración es obligatoria.',
        'duration_years.integer' => 'La duración debe ser un número entero.',
        'duration_years.min' => 'La duración debe ser al menos 1 año.',
        'duration_years.max' => 'La duración no puede exceder 10 años.',
        'faculty.required' => 'La facultad es obligatoria.',
        'language.required' => 'El idioma es obligatorio.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['careerId', 'name', 'code', 'study_level', 'duration_years', 'faculty']);
        $this->language = 'Español';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        UniversityCareer::create([
            'name' => $this->name,
            'code' => $this->code,
            'study_level' => $this->study_level,
            'duration_years' => $this->duration_years,
            'faculty' => $this->faculty,
            'language' => $this->language,
        ]);

        session()->flash('message', 'Carrera universitaria creada exitosamente.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $career = UniversityCareer::findOrFail($id);
        $this->careerId = $career->id;
        $this->name = $career->name;
        $this->code = $career->code;
        $this->study_level = $career->study_level;
        $this->duration_years = $career->duration_years;
        $this->faculty = $career->faculty;
        $this->language = $career->language;
        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:university_careers,code,' . $this->careerId,
            'study_level' => 'required|string|max:50',
            'duration_years' => 'required|integer|min:1|max:10',
            'faculty' => 'required|string|max:100',
            'language' => 'required|string|max:50',
        ]);

        $career = UniversityCareer::findOrFail($this->careerId);
        $career->update([
            'name' => $this->name,
            'code' => $this->code,
            'study_level' => $this->study_level,
            'duration_years' => $this->duration_years,
            'faculty' => $this->faculty,
            'language' => $this->language,
        ]);

        session()->flash('message', 'Carrera universitaria actualizada exitosamente.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $career = UniversityCareer::findOrFail($id);
        
        if ($career->groups()->count() > 0) {
            session()->flash('error', 'No se puede eliminar esta carrera porque tiene grupos asociados.');
            return;
        }

        $career->delete();
        session()->flash('message', 'Carrera universitaria eliminada exitosamente.');
    }

    public function openSubjectsModal($careerId)
    {
        $this->selectedCareer = UniversityCareer::with('subjects')->findOrFail($careerId);
        $this->selectedSubjects = $this->selectedCareer->subjects->pluck('id')->toArray();
        
        // Cargar datos de pivot
        foreach ($this->selectedCareer->subjects as $subject) {
            $this->subjectSemesters[$subject->id] = $subject->pivot->semester;
            $this->subjectIsRequired[$subject->id] = $subject->pivot->is_required;
        }
        
        $this->isSubjectsModalOpen = true;
    }

    public function closeSubjectsModal()
    {
        $this->isSubjectsModalOpen = false;
        $this->reset(['selectedCareer', 'selectedSubjects', 'subjectSemesters', 'subjectIsRequired']);
    }

    public function saveSubjects()
    {
        $syncData = [];
        
        foreach ($this->selectedSubjects as $subjectId) {
            $syncData[$subjectId] = [
                'semester' => $this->subjectSemesters[$subjectId] ?? null,
                'is_required' => $this->subjectIsRequired[$subjectId] ?? true,
            ];
        }
        
        $this->selectedCareer->subjects()->sync($syncData);
        
        session()->flash('message', 'Materias asignadas exitosamente.');
        $this->closeSubjectsModal();
    }

    public function render()
    {
        $careers = UniversityCareer::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orWhere('faculty', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        $allSubjects = Subject::where('is_active', true)->orderBy('name')->get();

        return view('livewire.academic-management.university-career-manager', [
            'careers' => $careers,
            'allSubjects' => $allSubjects,
        ]);
    }
}
