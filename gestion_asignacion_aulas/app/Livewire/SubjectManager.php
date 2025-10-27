<?php

namespace App\Livewire;

use App\Livewire\Forms\SubjectForm;
use App\Models\Subject;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SubjectManager extends Component
{
    use WithPagination;

    public $search = '';
    public $show = false;
    public $editing = null;
    protected $pagination_theme = 'tailwind';

    public SubjectForm $form;

    protected $listeners = ['refreshComponent' => 'render'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Subject::query();
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'ILIKE', $searchTerm)
                    ->orWhere('code', 'ILIKE', $searchTerm)
                    ->orWhere('credits', 'ILIKE', $searchTerm);
            });
        }

        $subjects = $query->orderBy('name')->paginate(10);
        return view('livewire.subject.subject-manager', compact('subjects'));
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $this->resetErrorBag();

        $subject = Subject::find($id);

        if (!$subject) {
            session()->flash('error', 'Subject not found.');
            return;
        }

        $this->editing = $subject->id;
        $this->form->set($subject);
        $this->show = true;
    }

    public function save(): void
    {
        $this->form->code = strtoupper(trim((string) $this->form->code));
        $this->form->editing_id = $this->editing;
        $this->form->validate();

        try {
            $subjectData = $this->form->getData();

            if ($this->editing) {
                $subject = Subject::find($this->editing);
                if (!$subject) {
                    session()->flash('error', 'Subject not found.');
                    return;
                }

                $subject->update($subjectData);

                session()->flash('success', 'Subject updated successfully.');

            } else {
                Subject::create($subjectData);
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
            $subject = Subject::find($id);
            if ($subject) {
                $subject->delete();
                session()->flash('message', 'Materia eliminada correctamente');
            } else
                session()->flash('error', 'Materia no encontrada');
        } catch (Exception) {
            session()->flash('error', 'Error al eliminar la materia');
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
