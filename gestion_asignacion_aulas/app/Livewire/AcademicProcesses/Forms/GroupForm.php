<?php

namespace App\Livewire\AcademicProcesses\Forms;

use App\Models\Group;
use App\Models\Subject;
use Illuminate\Validation\Rule;
use Livewire\Form;


class GroupForm extends Form
{
    public ?int $editing_id = null;

    public string $name = '';
    public bool $is_active = true;
    public array $subjects = [];

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:3',
            'is_active' => 'required|boolean',
            'subjects' => 'nullable|array',
        ];
    }

    public function set(Group $group): void
    {
        $this->editing_id = $group->id;
        $this->name = $group->name;
        $this->is_active = $group->is_active ? 1 : 0;
        $this->subjects = $group->subjects ? $group->subjects->pluck('id')->toArray() : [];
    }

    public function getData(): array
    {
        return [
            'name' => $this->name,
            'is_active' => $this->is_active,
        ];
    }


    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->is_active = 1;
        $this->editing_id = null;
        $this->subjects = [];
    }
}
