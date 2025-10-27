<?php

namespace App\Livewire\AcademicLogistics\Forms;

use App\Models\Classroom;
use Illuminate\Validation\Rule;
use Livewire\Form;
class ClassroomForm extends Form
{

    public ?int $editing_id = null;
    public ?int $module_id = null;
    public int $number = 0;
    public string $type = '';
    public int $capacity = 0;
    public bool $is_active = true;

    public function rules(): array
    {
        return [
            'module_id' => [
                'required',
                'integer',
                Rule::exists('modules', 'id')
            ],

            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('classrooms')->where(function ($query) {
                    return $query->where('module_id', $this->module_id);
                })->ignore($this->editing_id),
            ],

            'type' => ['required', 'string', Rule::in(['aula', 'laboratorio pcs', 'auditorio', 'biblioteca', 'laboratorio fisica'])],
            'capacity' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ];
    }

    public function set(int $id): void
    {
        $classroom = Classroom::findOrFail($id);

        $this->editing_id = $classroom->id;
        $this->module_id = $classroom->module_id;
        $this->number = $classroom->number;
        $this->type = $classroom->type;
        $this->capacity = $classroom->capacity;
        $this->is_active = $classroom->is_active;
    }

    public function getData(): array
    {
        return [
            'module_id' => $this->module_id,
            'number' => $this->number,
            'type' => $this->type,
            'capacity' => $this->capacity,
            'is_active' => $this->is_active,
        ];
    }

    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->resetValidation();
    }
}
