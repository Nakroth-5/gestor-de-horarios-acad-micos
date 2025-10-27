<?php

namespace App\Livewire\AcademicLogistics;

use App\Livewire\AcademicLogistics\Forms\ClassroomForm;
use App\Models\Classroom;
use App\Models\Module;
use Exception;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout( 'layouts.app')]
class ClassroomManager extends Component
{
    use WithPagination;
    public ClassroomForm $form;

    public $editing = null;
    public $search = '';
    public $show = false;

    protected $listeners = ['refreshComponent' => 'render'];
    protected $pagination_theme = 'tailwind';
    public function render(): View
    {
        $classrooms = Classroom::query()
            ->join('modules', 'classrooms.module_id', '=', 'modules.id');

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $classrooms->where(function ($q) use ($searchTerm) {

                $q->where('classrooms.number', 'ILIKE', $searchTerm)
                    ->orWhere('classrooms.type', 'ILIKE', $searchTerm)
                    ->orWhere('classrooms.capacity', 'ILIKE', $searchTerm)

                    ->orWhere('modules.code', 'ILIKE', $searchTerm)
                    ->orWhere('modules.address', 'ILIKE', $searchTerm);

            });
        }

        $classrooms->select('classrooms.*');

        $classrooms = $classrooms->orderBy('classrooms.id')->paginate(10);
        $allModules = Module::all();
        return view('livewire.academic-logistics.classroom.classroom-manager', compact('classrooms', 'allModules'));

    }

    public function edit($id): void
    {
        $this->resetErrorBag();
        $classroom = Classroom::find($id);
        if (!$classroom) {
            session()->flash('error', 'Classroom not found.');
            return;
        }
        $this->editing = $classroom->id;
        $this->form->set($classroom->id);
        $this->show = true;
    }

    public function save(): void
    {
        //dd($this->form->all());
        $this->form->editing_id = $this->editing;
        $this->form->validate();

        try {
            $classroomData = $this->form->getData();
            if ($this->editing) {
                $classroom = Classroom::find($this->editing);
                if (!$classroom) {
                    session()->flash('error', 'Classroom not found.');
                    return;
                }
                $classroom->update($classroomData);
            } else {
                Classroom::create($classroomData);
                session()->flash('success', 'Classroom created successfully.');
            }
            $this->closeModal();
        } catch (Exception $e) {
            session()->flash('error', 'Error saving the classroom: ' . $e->getMessage());
        }
    }

    public function delete($id): void
    {
        try {
            $classroom = Classroom::find($id);
            if ($classroom) {
                $classroom->update(['is_active' => false]);
                session()->flash('message', 'Classroom deleted successfully');
            } else {
                session()->flash('error', 'Classroom not found');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Error deleting the classroom: ' . $e->getMessage());
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
