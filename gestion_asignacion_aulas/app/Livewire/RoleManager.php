<?php

namespace App\Livewire;

use App\Livewire\Forms\RoleForm;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RoleManager extends Component
{
    use WithPagination;

    public $search = '';
    public $show = false;
    public $editing = null;
    protected $pagination_theme = 'tailwind';

    public RoleForm $form;

    protected $listeners = ['refreshComponent' => 'render'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Role::query();
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'ILIKE', $searchTerm)
                    ->orWhere('description', 'ILIKE', $searchTerm)
                    ->orWhere('level', 'ILIKE', $searchTerm);
            });
        }

        $roles = $query->orderBy('name')->paginate(10);
        $allPermissions = Permission::all();
        return view('livewire.role.role-manager', compact('roles', 'allPermissions'));
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $this->resetErrorBag();

        $role = Role::find($id);

        if (!$role) {
            session()->flash('error', 'Role not found.');
            return;
        }

        $this->editing = $role->id;
        $this->form->set($role);
        $this->show = true;
    }

    public function save(): void
    {
        $this->form->editing_id = $this->editing;
        $this->form->validate();

        try {
            $roleData = $this->form->getData();

            if ($this->editing) {
                $role = Role::find($this->editing);
                if (!$role) {
                    session()->flash('error', 'Role not found.');
                    return;
                }

                $role->update($roleData);

                $role->permissions()->sync($this->form->permissions);;
                session()->flash('success', 'Role updated successfully.');

            } else {
                $role = Role::create($roleData);
                $role->permissions()->sync($this->form->permissions);
                session()->flash('success', 'Role created successfully.');
            }

            $this->closeModal();

        } catch (Exception $e) {
            Log::error('Error al guardar rol: ' . $e->getMessage());
            session()->flash('error', 'Error al guardar el rol: ' . $e->getMessage());
        }
    }

    public function delete($id): void
    {
        try {
            $role = Role::find($id);
            if ($role) {
                $role->update(['is_active' => false]);
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
