<?php

namespace App\Observers;

use App\Auditable;
use App\Models\DaySchedule;
use Carbon\Carbon;

class ScheduleBlockObserver
{
    use Auditable;
    /**
     * Handle the DaySchedule "created" event.
     */
    public function created(DaySchedule $daySchedule): void
    {
        $dayName = $daySchedule->day->name;
        $start = $daySchedule->schedule->start;
        $end = $daySchedule->schedule->end;

        $this->logAction('created', $daySchedule,
            "A creado al horario { $dayName }, [ $start - $end ]");
    }

    /**
     * Handle the DaySchedule "updated" event.
     */
    public function updated(DaySchedule $daySchedule): void
    {
        $dayName = $daySchedule->day->name;
        $start = $daySchedule->schedule->start;
        $end = $daySchedule->schedule->end;

        $this->logAction('updated', $daySchedule,
            "A actualizado al horario { $dayName }, [ $start - $end ]");
    }

    /**
     * Handle the DaySchedule "deleted" event.
     */
    public function deleted(DaySchedule $daySchedule): void
    {
        $dayName = $daySchedule->day->name;
        $start = $daySchedule->schedule->start;
        $end = $daySchedule->schedule->end;

        $this->logAction('deleted', $daySchedule,
            "A eliminado al horario { $dayName }, [ $start - $end ]");
    }

    /**
     * Handle the DaySchedule "restored" event.
     */
    public function restored(DaySchedule $daySchedule): void
    {
        //
    }

    /**
     * Handle the DaySchedule "force deleted" event.
     */
    public function forceDeleted(DaySchedule $daySchedule): void
    {
        //
    }
}
