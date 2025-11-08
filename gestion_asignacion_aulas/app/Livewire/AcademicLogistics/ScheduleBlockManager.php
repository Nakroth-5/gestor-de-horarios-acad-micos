<?php

namespace App\Livewire\AcademicLogistics;

use App\Livewire\AcademicLogistics\Forms\ScheduleBlockForm;
use App\Models\Schedule;
use App\Models\Day;
use App\Models\DaySchedule;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ScheduleBlockManager extends Component
{
    use WithPagination;

    public $search = '';
    public $show = false;
    public $editing = null;
    protected $pagination_theme = 'tailwind';

    public $allDays = [];
    public $allSchedules = [];

    public ScheduleBlockForm $form;

    protected $listeners = ['refreshComponent' => 'render'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = DaySchedule::query()->with(['day', 'schedule']);
        if (!empty($this->search)) {
            $searchTerm = '%' . mb_strtolower($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('day', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(name) LIKE ?', [$searchTerm]);
                })->orWhereHas('schedule', function ($q3) use ($searchTerm) {
                    $q3->where('start', 'LIKE', $searchTerm)
                        ->orWhere('end', 'LIKE', $searchTerm);
                });
            });
        }

        $dayOrder = "CASE
            WHEN days.name = 'Monday' THEN 1
            WHEN days.name = 'Tuesday' THEN 2
            WHEN days.name = 'Wednesday' THEN 3
            WHEN days.name = 'Thursday' THEN 4
            WHEN days.name = 'Friday' THEN 5
            WHEN days.name = 'Saturday' THEN 6
            WHEN days.name = 'Sunday' THEN 7
            ELSE 8
          END";

        $daySchedules = $query->join('days', 'day_schedules.day_id', '=', 'days.id')
            ->join('schedules', 'day_schedules.schedule_id', '=', 'schedules.id')
            ->select('day_schedules.*')
            ->orderByRaw($dayOrder)
            ->orderBy('schedules.start')
            ->paginate(10);


        $this->allDays = Day::all();
        $this->allSchedules = Schedule::all();
        return view('livewire.academic-logistics.schedule_block.schedule-block-manager', compact('daySchedules'));
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $this->resetErrorBag();

        $daySchedule = DaySchedule::find($id);

        if (!$daySchedule) {
            session()->flash('error', 'Day schedule not found.');
            return;
        }

        $this->editing = $daySchedule->id;
        $this->form->set($daySchedule);
        $this->show = true;
    }

    public function save(): void
    {
        $this->form->editing_id = $this->editing;

        if ($this->form->schedule_id === 'new') {
            $this->saveNewSchedule();
        }

        $this->form->validate();

        try {
            if ($this->editing) {
                // MODO EDICIÓN: Solo actualizar el registro existente
                $daySchedule = DaySchedule::find($this->editing);
                if (!$daySchedule) {
                    session()->flash('error', 'Day schedule not found.');
                    return;
                }

                // En edición, solo usar el primer día seleccionado
                $daySchedule->update([
                    'day_id' => $this->form->day_ids[0],
                    'schedule_id' => $this->form->schedule_id,
                ]);

                session()->flash('success', 'Day schedule updated successfully.');

            } else {
                // MODO CREACIÓN: Crear múltiples registros
                $created = 0;
                $skipped = 0;
                $scheduleId = $this->form->schedule_id;

                foreach ($this->form->day_ids as $dayId) {
                    // Verificar si ya existe esta combinación
                    $exists = DaySchedule::where('day_id', $dayId)
                        ->where('schedule_id', $scheduleId)
                        ->exists();

                    if (!$exists) {
                        DaySchedule::create([
                            'day_id' => $dayId,
                            'schedule_id' => $scheduleId,
                        ]);
                        $created++;
                    } else {
                        $skipped++;
                    }
                }

                if ($created > 0 && $skipped > 0) {
                    session()->flash('success', "{$created} bloque(s) creado(s). {$skipped} ya existía(n).");
                } elseif ($created > 0) {
                    session()->flash('success', "{$created} bloque(s) de horario creado(s) exitosamente.");
                } else {
                    session()->flash('warning', 'Todos los bloques ya existían.');
                }
            }

            $this->closeModal();

        } catch (Exception $e) {
            Log::error('Error al guardar el bloque de horario: ' . $e->getMessage());
            session()->flash('error', 'Error al guardar el bloque de horario: ' . $e->getMessage());
        }
    }

    public function delete($id): void
    {
        try {
            $daySchedule = DaySchedule::find($id);
            if ($daySchedule) {
                $daySchedule->delete();
                session()->flash('message', 'Bloque de horario eliminado correctamente');
            } else
                session()->flash('error', 'Bloque de horario no encontrado');
        } catch (Exception) {
            session()->flash('error', 'Error al eliminar el bloque de horario');
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

    public function saveNewSchedule(): void
    {
        $newScheduleStart = $this->form->schedule_start;
        $newScheduleEnd = $this->form->schedule_end;

        if (empty($newScheduleStart) || empty($newScheduleEnd)) {
            $this->addError('schedule_id', 'Debe proporcionar la hora de inicio y fin para el nuevo horario.');
            return;
        }

        // Validar que end sea después de start
        if ($newScheduleEnd <= $newScheduleStart) {
            $this->addError('schedule_end', 'La hora de fin debe ser posterior a la de inicio.');
            return;
        }

        $newSchedule = Schedule::firstOrCreate([
            'start' => $newScheduleStart,
            'end' => $newScheduleEnd,
        ]);

        $this->form->schedule_id = $newSchedule->id;
        $this->form->schedule_start = '';
        $this->form->schedule_end = '';

        $this->getRelations();
    }

    public function mount(): void
    {
        $this->form->reset();
        $this->getRelations();
    }

    public function getRelations(): void
    {
        $this->allDays = Day::all();
        $this->allSchedules = Schedule::all();
    }
}
