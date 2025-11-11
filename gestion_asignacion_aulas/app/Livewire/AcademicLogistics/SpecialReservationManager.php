<?php

namespace App\Livewire\AcademicLogistics;

use App\Models\SpecialReservation;
use App\Models\Classroom;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class SpecialReservationManager extends Component
{
    use WithPagination;

    public $classroom_id;
    public $event_type;
    public $title;
    public $description;
    public $reservation_date;
    public $start_time;
    public $end_time;
    public $estimated_attendees;
    public $reservationId;
    public $showModal = false;
    public $isEditing = false;
    public $filterStatus = '';
    public $search = '';

    protected $rules = [
        'classroom_id' => 'required|exists:classrooms,id',
        'event_type' => 'required|string',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'reservation_date' => 'required|date|after_or_equal:today',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'estimated_attendees' => 'nullable|integer|min:1',
    ];

    public function render()
    {
        $reservations = SpecialReservation::with(['classroom', 'user', 'approvedBy'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy('reservation_date', 'desc')
            ->paginate(10);

        $classrooms = Classroom::all()->sortBy('name');

        return view('livewire.academic-logistics.special-reservation-manager', [
            'reservations' => $reservations,
            'classrooms' => $classrooms,
            'isAdmin' => $this->isAdmin(),
        ]);
    }

    public function isAdmin()
    {
        return Auth::user()->roles()->where('name', 'Administrador')->exists();
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

        SpecialReservation::create([
            'classroom_id' => $this->classroom_id,
            'user_id' => Auth::id(),
            'event_type' => $this->event_type,
            'title' => $this->title,
            'description' => $this->description,
            'reservation_date' => $this->reservation_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'estimated_attendees' => $this->estimated_attendees,
            'status' => 'pendiente',
        ]);

        session()->flash('success', 'Reserva creada. Pendiente de aprobación.');
        $this->closeModal();
    }

    public function approve($id)
    {
        $reservation = SpecialReservation::findOrFail($id);
        $reservation->update([
            'status' => 'aprobada',
            'approved_by' => Auth::id(),
        ]);

        session()->flash('success', 'Reserva aprobada.');
    }

    public function reject($id)
    {
        $reservation = SpecialReservation::findOrFail($id);
        $reservation->update([
            'status' => 'rechazada',
            'approved_by' => Auth::id(),
        ]);

        session()->flash('success', 'Reserva rechazada.');
    }

    public function delete($id)
    {
        SpecialReservation::findOrFail($id)->delete();
        session()->flash('success', 'Reserva eliminada.');
    }

    private function resetForm()
    {
        $this->reset([
            'classroom_id', 'event_type', 'title', 'description',
            'reservation_date', 'start_time', 'end_time', 'estimated_attendees',
            'reservationId', 'isEditing'
        ]);
        $this->resetValidation();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
