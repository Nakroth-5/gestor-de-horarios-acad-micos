<?php

namespace App\Livewire\SecurityAccess;

use App\Livewire\SecurityAccess\Forms\UserForm;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $show = false;
    public $editing = null;
    protected $pagination_theme = 'tailwind';

    public UserForm $form;

    protected $listeners = ['refreshComponent' => 'render'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = User::query();

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'ILIKE', $searchTerm)
                    ->orWhere('last_name', 'ILIKE', $searchTerm)
                    ->orWhere('email', 'ILIKE', $searchTerm)
                    ->orWhere('phone', 'ILIKE', $searchTerm)
                    ->orWhere('document_number', 'ILIKE', $searchTerm)
                    ->orWhereRaw("CONCAT(name, ' ', last_name) ILIKE ?", [$searchTerm]);
            });
        }
        $users = $query->orderBy('name')->paginate(10);
        $allRoles = Role::all();
        return view('livewire.security-access.user.user-manager', compact('users', 'allRoles'));
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $this->resetErrorBag();

        $user = User::find($id);

        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        $this->editing = $user->id;

        $this->form->set($user);
        $this->show = true;
    }

    public function save(): void
    {
        //dd($this->form->all());
        $this->form->editing_id = $this->editing;
        $this->form->validate();

        try {
            $userData = $this->form->getData();
            $passwordData = $this->form->getPasswordData();

            if ($this->editing) {
                $user = User::find($this->form->editing_id);
                if (!$user) {
                    session()->flash('error', 'User not found.');
                    return;
                }

                if ($passwordData)
                    $userData['password'] = $passwordData;

                $user->update($userData);
                $user->roles()->sync($this->form->roles);
                session()->flash('success', 'User updated successfully.');
            } else {
                $userData['password'] = $passwordData;
                $user = User::create($userData);
                $user->roles()->sync($this->form->roles);
                session()->flash('success', 'User created successfully.');
            }
            $this->closeModal();
        } catch (Exception $e) {
            Log::error('Error al guardar usuario: ' . $e->getMessage());
            session()->flash('error', 'Error al guardar el usuario: ' . $e->getMessage());
        }
    }

    public function delete($id): void
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                session()->flash('message', 'Usuario eliminado correctamente');
            } else
                session()->flash('error', 'Usuario no encontrado');
        } catch (Exception) {
            session()->flash('error', 'Error al eliminar el usuario');
        }
    }

    public function openCreateModal(): void
    {
        $this->form->reset();
        $this->editing = null;
        $this->show = true;
    }

    public function closeModal(): void
    {
        $this->show = false;
        $this->form->reset();
        $this->dispatch('modal-closed');
    }

    public function mount(): void
    {
        $this->form->reset();
    }
}
