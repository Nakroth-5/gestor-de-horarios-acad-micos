<?php

namespace App\Livewire\AcademicProcesses\Forms;

use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubject;
use Illuminate\Validation\Rule;
use Livewire\Form;


class TeacherSubjectForm extends Form
{
    public ?int $user_id = null;
    public array $subject_ids = [];
    public array $subject_careers = []; // Carrera por cada materia

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'required|exists:subjects,id',
            'subject_careers.*' => 'nullable|exists:university_careers,id',
        ];
    }

    /**
     * Cargar datos del docente para edición
     */
    public function set(User $user): void
    {
        $this->user_id = $user->id;

        // Cargar las materias asignadas actualmente al docente con sus carreras
        $userSubjects = UserSubject::where('user_id', $user->id)->get();
        $this->subject_ids = $userSubjects->pluck('subject_id')->toArray();
        
        // Cargar las carreras asociadas
        foreach ($userSubjects as $us) {
            $this->subject_careers[$us->subject_id] = $us->university_career_id;
        }
    }

    /**
     * Obtener los datos para guardar
     */
    public function getData(): array
    {
        return [
            'user_id' => $this->user_id,
            'subject_ids' => $this->subject_ids,
            'subject_careers' => $this->subject_careers,
        ];
    }

    /**
     * Reset del formulario
     */
    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->user_id = null;
        $this->subject_ids = [];
        $this->subject_careers = [];
    }
}
