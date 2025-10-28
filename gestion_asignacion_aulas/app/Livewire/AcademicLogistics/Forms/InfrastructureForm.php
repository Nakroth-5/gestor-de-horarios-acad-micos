<?php

namespace App\Livewire\AcademicLogistics\Forms;

use App\Models\Module;
use Illuminate\Validation\Rule;
use Livewire\Form;

class InfrastructureForm extends Form
{
    public ?int $editing_id = null;
    public int $code = 0;
    public string $address = '';
    public bool $is_active = true;

    public function rules(): array
    {
        return [
            'code' => ['required', 'integer', 'min:1', Rule::unique('modules', 'code')->ignore($this->editing_id)],
            'address' => 'required|string',
            'is_active' => 'required|boolean',
        ];
    }

    public function set(Module $module): void
    {
        $this->editing_id = $module->id;
        $this->code = $module->code;
        $this->address = $module->address;
        $this->is_active = $module->is_active ? 1 : 0;
    }

    public function getData(): array
    {
        return [
            'code' => $this->code,
            'address' => $this->address,
            'is_active' => $this->is_active,
        ];
    }

    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->editing_id = null;
        $this->code = 0;
        $this->address = '';
        $this->is_active = true;
    }
}
