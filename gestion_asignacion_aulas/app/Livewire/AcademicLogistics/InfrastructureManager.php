<?php

namespace App\Livewire\AcademicLogistics;

use App\Livewire\AcademicLogistics\Forms\InfrastructureForm;
use App\Models\Module;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class InfrastructureManager extends Component
{
    use WithPagination;
    public InfrastructureForm $form;
    public $editing = null;
    public $show = false;
    public $search = '';

    protected $listeners = ['refreshComponent' => 'render'];
    protected $pagination_theme = 'tailwind';

    public function render(): View
    {
        $infrastructure = Module::query();
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $infrastructure->where(function ($q) use ($searchTerm) {
                $q->where('id', 'ILIKE', $searchTerm)
                    ->orWhere('code', 'ILIKE', $searchTerm)
                    ->orWhere('address', 'ILIKE', $searchTerm);
            });
        }

        $infrastructure = $infrastructure->orderBy('id')->paginate(10);
        return view('livewire.academic-logistics.module.infrastructure-manager', compact('infrastructure'));
    }

    public function edit($id): void
    {
        $this->resetErrorBag();

        $module = Module::find($id);
        if (!$module) {
            session()->flash('error', 'Module not found.');
            return;
        }

        $this->editing = $module->id;
        $this->form->set($module);
        $this->show = true;
    }

    public function save(): void
    {
        //dd($this->form->all());
        $this->form->editing_id = $this->editing;
        $this->form->validate();

        try {
            $moduleData = $this->form->getData();
            if ($this->editing) {
                $module = Module::find($this->editing);
                if (!$module) {
                    session()->flash('error', 'Module not found.');
                    return;
                }
                $module->update($moduleData);
            } else {
                Module::create($moduleData);
                session()->flash('success', 'Module created successfully.');
            }
            $this->closeModal();
        } catch (Exception $e) {
            Log::error('Error saving the module: ' . $e->getMessage());
            session()->flash('error', 'Error saving the module: ' . $e->getMessage());
        }
    }

    public function delete($id): void
    {
        try {
            $module = Module::find($id);
            if ($module) {
                $module->update(['is_active' => false]);;
                session()->flash('message', 'Module deleted successfully');
            } else
                session()->flash('error', 'Module no found');
        } catch (Exception) {
            Log::error('Error deleting the module');
            session()->flash('error', 'Error deleting the module');
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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }
}
