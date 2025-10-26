<?php

namespace App\Livewire\Forms;


use App\Models\Role;
use Livewire\Form;

class RoleForm extends Form
{
    public ?int $editing_id = null;
    public string $name = '';
    public string $description = '';
    public int $level = 0;
    public bool $is_active = true;
    public string $module = '';

    public array $permissions = [];

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'level' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'permissions' => 'nullable|array',
        ];
    }

    public function set(Role $role): void
    {
        $this->editing_id = $role->id;
        $this->name = $role->name;
        $this->description = $role->description ?? '';
        $this->level = $role->level;
        $this->is_active = $role->is_active ? 1 : 0;

        $this->permissions = $role->permissions ? $role->permissions->pluck('id')->toArray() : [];
    }

    public function getData(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'level' => $this->level,
            'is_active' => $this->is_active,
        ];
    }


    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->permissions = [];
        $this->module = '';
        $this->is_active = 1;
        $this->editing_id = null;
    }
}
