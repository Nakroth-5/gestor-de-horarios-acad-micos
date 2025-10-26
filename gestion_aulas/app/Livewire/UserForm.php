<?php

namespace app\Livewire;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class UserForm extends Form
{
    public ?int $editing_id = null;
    public int $code = 0;
    public string $name = '';
    public string $last_name = '';
    public string $phone = '';
    public string $address = '';
    public string $document_type = 'CI';
    public int $document_number = 0;
    public bool $is_active = true;
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public array $roles = [];

    public function rules(): array
    {
        $userId = $this->editing_id;

        $rules = [
            'name' => 'required|string|max:50',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($userId)],
            'document_type' => ['required', 'string', Rule::in(['CI', 'PASSPORT'])],
            'document_number' => ['required', 'integer', 'digits_between:5,12'],
            'is_active' => 'required|boolean',
            'roles' => 'nullable|array',
        ];

        if ($userId) {
            $rules['password'] = ['nullable', 'string', Password::defaults(), 'confirmed'];
        } else {
            $rules['password'] = ['required', 'string', Password::defaults(), 'confirmed'];
        }

        return $rules;
    }

    public function set(User $user): void
    {
        $this->editing_id = $user->id;
        $this->code = $user->code;
        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->phone = $user->phone ?? '';
        $this->document_type = $user->document_type;
        $this->document_number = $user->document_number;
        $this->address = $user->address ?? '';
        $this->email = $user->email;
        $this->is_active = $user->is_active ? 1 : 0;
        $this->roles = $user->roles ? $user->roles->pluck('id')->toArray() : [];
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function getData(): array
    {
        return [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];
    }

    public function getPasswordData(): ?string
    {
        if (!empty($this->password)) {
            return Hash::make($this->password);
        }
        return null;
    }

    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->roles = [];
        $this->is_active = 1;
        $this->editing_id = null;
    }
}
