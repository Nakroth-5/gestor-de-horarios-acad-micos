<?php

namespace App\Livewire\AcademicLogistics\Forms;

use Livewire\Form;

class ManualAssignmentForm extends Form
{
    public ?int $id = null;
    public ?int $user_subject_id = null;
    public ?int $group_id = null;
    public ?int $classroom_id = null;
    public ?int $day_schedule_id = null;
    public ?int $academic_id = null;

    public function rules(): array
    {
        return [
            'user_subject_id' => 'required|exists:user_subjects,id',
            'group_id' => 'required|exists:groups,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day_schedule_id' => 'required|exists:day_schedules,id',
            'academic_id' => 'required|exists:academic_management,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_subject_id.required' => 'La materia y docente son obligatorios.',
            'group_id.required' => 'El grupo es obligatorio.',
            'classroom_id.required' => 'El aula es obligatoria.',
            'day_schedule_id.required' => 'El horario es obligatorio.',
            'user_subject_id.exists' => 'La combinación docente-materia seleccionada no existe.',
            'group_id.exists' => 'El grupo seleccionado no existe.',
            'classroom_id.exists' => 'El aula seleccionada no existe.',
            'day_schedule_id.exists' => 'El horario seleccionado no existe.',
            'academic_id.exists' => 'El periodo académico seleccionado no existe.',
        ];
    }

    public function setAssignment($assignment): void
    {
        $this->id = $assignment->id;
        $this->user_subject_id = $assignment->user_subject_id;
        $this->group_id = $assignment->group_id;
        $this->classroom_id = $assignment->classroom_id;
        $this->day_schedule_id = $assignment->day_schedule_id;
        $this->academic_id = $assignment->academic_management_id;
    }

    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->resetValidation();
    }
}

