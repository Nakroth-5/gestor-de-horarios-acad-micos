<?php

namespace App\Livewire\AcademicLogistics\Forms;

use App\Models\Schedule;
use App\Models\DaySchedule;
use Illuminate\Validation\Rule;
use Livewire\Form;


class ScheduleBlockForm extends Form
{
    public ?int $editing_id = null;


    public array $day_ids = [];
    public int|string|null $schedule_id = null;

    // Schedule Block Information
    public string $day_name = '';
    public string $schedule_start = '';
    public string $schedule_end = '';

    public function rules(): array
    {
        return [
            'day_ids' => 'required|array|min:1',
            'day_ids.*' => 'required|exists:days,id',
            'schedule_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value === 'new') {
                        // Si es 'new', validar que existan start y end
                        if (empty($this->schedule_start) || empty($this->schedule_end)) {
                            $fail('Debe proporcionar hora de inicio y fin para el nuevo horario.');
                        }
                    } else {
                        // Si no es 'new', validar que exista en schedules
                        if (!Schedule::where('id', $value)->exists()) {
                            $fail('El horario seleccionado no existe.');
                        }
                        
                        /*foreach ($this->day_ids as $dayId) {
                            // Validar que la combinación día+horario sea única
                            $exists = DaySchedule::where('day_id', $dayId)
                                ->where('schedule_id', $value)
                                ->where('id', '!=', $this->editing_id)
                                ->exists();
                            
                            if ($exists) {
                                $fail('Ya existe un bloque con ese día y horario.');
                            }
                        }*/
                    }
                }
            ],
            'schedule_start' => 'required_if:schedule_id,new|nullable|date_format:H:i',
            'schedule_end' => 'required_if:schedule_id,new|nullable|date_format:H:i|after:schedule_start',
        ];
    }
    public function set(DaySchedule $daySchedule): void
    {
        $this->editing_id = $daySchedule->id;
        $this->day_ids = [$daySchedule->day_id];
        $this->schedule_id = $daySchedule->schedule_id;    
    }

    public function getData(): array
    {
        return [
            'day_ids' => $this->day_ids,
            'schedule_id' => $this->schedule_id,
        ];
    }


    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->editing_id = null;
        $this->day_ids = [];
    }


    public function messages(): array
    {
        return [
            'schedule_end.unique' => 'Ya existe un bloque con ese día, horario y hora de inicio.',
            'schedule_end.after' => 'La hora de fin debe ser posterior a la de inicio.',
        ];
    }
}
