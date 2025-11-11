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

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'required|exists:subjects,id',
        ];
    }

    /**
     * Cargar datos del docente para edición
     */
    public function set(User $user): void
    {
        $this->user_id = $user->id;

        // Cargar las materias asignadas actualmente al docente usando la relación
        $this->subject_ids = $user->subjects()->pluck('subjects.id')->toArray();
    }

    /**
     * Obtener los datos para guardar
     */
    public function getData(): array
    {
        return [
            'user_id' => $this->user_id,
            'subject_ids' => $this->subject_ids,
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
    }
}
