<?php

namespace App\Livewire\AcademicProcesses;

use App\Models\AcademicManagement;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AcademicPeriodManager extends Component
{
    use WithPagination;

    public $name;
    public $start_date;
    public $end_date;
    public $periodId;
    public $showModal = false;
    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ];

    protected $messages = [
        'name.required' => 'El nombre del periodo es requerido.',
        'start_date.required' => 'La fecha de inicio es requerida.',
        'start_date.date' => 'La fecha de inicio debe ser válida.',
        'end_date.required' => 'La fecha de fin es requerida.',
        'end_date.date' => 'La fecha de fin debe ser válida.',
        'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
    ];

    public function render()
    {
        $periods = AcademicManagement::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('start_date', 'like', '%' . $this->search . '%')
                    ->orWhere('end_date', 'like', '%' . $this->search . '%');
            })
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('livewire.academic-processes.academic-period-manager', [
            'periods' => $periods,
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function store()
    {
        $this->validate();

        try {
            AcademicManagement::create([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            session()->flash('success', 'Periodo académico creado exitosamente.');
            $this->closeModal();
            $this->reset(['search']);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el periodo: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $period = AcademicManagement::findOrFail($id);
        
        $this->periodId = $period->id;
        $this->name = $period->name;
        $this->start_date = $period->start_date->format('Y-m-d');
        $this->end_date = $period->end_date->format('Y-m-d');
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        try {
            $period = AcademicManagement::findOrFail($this->periodId);
            $period->update([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            session()->flash('success', 'Periodo académico actualizado exitosamente.');
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el periodo: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $period = AcademicManagement::findOrFail($id);
            $period->delete();

            session()->flash('success', 'Periodo académico eliminado exitosamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el periodo: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->reset(['name', 'start_date', 'end_date', 'periodId', 'isEditing']);
        $this->resetValidation();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
