<?php

namespace App\Observers;

use App\Models\DaySchedule;
use Carbon\Carbon;

class ScheduleBlockObserver
{
    /**
     * Handle the DaySchedule "created" event.
     */
    public function created(DaySchedule $daySchedule): void
    {
        // La auditoría automática del trait Auditable se encarga de esto
        // No necesitamos hacer nada manualmente aquí
    }

    /**
     * Handle the DaySchedule "updated" event.
     */
    public function updated(DaySchedule $daySchedule): void
    {
        // La auditoría automática del trait Auditable se encarga de esto
    }

    /**
     * Handle the DaySchedule "deleted" event.
     */
    public function deleted(DaySchedule $daySchedule): void
    {
        // La auditoría automática del trait Auditable se encarga de esto
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
