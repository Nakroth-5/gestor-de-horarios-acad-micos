<?php

namespace App\Livewire\AcademicProcesses;

use App\Livewire\AcademicProcesses\Forms\GroupForm;
use App\Models\Group;
use App\Models\Subject;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class GroupManager extends Component
{

    use WithPagination;

    public GroupForm $form;
    public $editing = null;
    public $search = '';
    public $show = false;

    protected $pagination_theme = 'tailwind';
    protected $listeners = ['refreshComponent' => 'render'];

    public function render(): View
    {
        $query = Group::query();
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'ILIKE', $searchTerm);
            });
        }

        $groups = $query->orderBy('name')->paginate(20);
        $allSubject = Subject::all();
        return view('livewire.academic-processes.group.group-manager',
            compact('groups', 'allSubject'));
    }

    public function edit($id): void
    {
        $this->getErrorBag();
        $group = Group::find($id);
        if (!$group) {
            session()->flash('error', 'Group not found');
            return;
        }

        $this->editing = $group->id;
        $this->form->set($group);
        $this->show = true;
    }

    public function save(): void
    {
        //dd($this->form->all());
        $this->form->editing_id = $this->editing;
        $this->validate();

        try {
            $groupData = $this->form->getData();

            if ($this->editing) {
                $group = Group::find($this->editing);
                if (!$group) {
                    session()->flash('error', 'Subject not found.');
                    return;
                }
                $group->update($groupData);
                session()->flash('success', 'Subject updated successfully.');

            } else {
                $group = Group::create($groupData);
                session()->flash('success', 'Subject created successfully.');
            }

            $this->closeModal();

        } catch (Exception $e) {
            Log::error('Error al guardar la materia: ' . $e->getMessage());
            session()->flash('error', 'Error al guardar la materia: ' . $e->getMessage());
        }

    }

    public function delete($id): void
    {
        try {
            $group = Group::find($id);
            if ($group) {
                $group->delete();
                //$group->update(['is_active' => false]);
                $this->dispatch('delete-group', group: $group->name);
                session()->flash('message', 'Usuario eliminado correctamente');
            } else
                session()->flash('error', 'Usuario no encontrado');
        } catch (Exception) {
            session()->flash('error', 'Error al eliminar el usuario');
        }
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
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
