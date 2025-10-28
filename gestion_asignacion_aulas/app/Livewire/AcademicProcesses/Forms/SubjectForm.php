<?php

namespace App\Livewire\AcademicProcesses\Forms;

use App\Models\Subject;
use Illuminate\Validation\Rule;
use Livewire\Form;


class SubjectForm extends Form
{
    public ?int $editing_id = null;

    public string $name = '';
    public string $code = '';
    public int $credits = 0;
    public bool $is_active = true;

    public function rules(): array
    {
        $subjectId = $this->editing_id;
        return [
            'name' => 'required|string|max:50',
            'code' => ['required', 'string', 'size:6', 'regex:/^[A-Z0-9]+$/', Rule::unique('subjects', 'code')->ignore($subjectId)],
            'credits' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ];
    }

    public function set(Subject $subject): void
    {
        $this->editing_id = $subject->id;
        $this->name = $subject->name;
        $this->code = $subject->code;
        $this->credits = $subject->credits;
        $this->is_active = $subject->is_active ? 1 : 0;
    }

    public function getData(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'credits' => $this->credits,
            'is_active' => $this->is_active,
        ];
    }


    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->is_active = 1;
        $this->editing_id = null;
    }
}
