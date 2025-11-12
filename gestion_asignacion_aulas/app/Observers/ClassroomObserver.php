<?php

namespace App\Observers;

use App\Models\Classroom;

class ClassroomObserver
{
    /**
     * Handle the Classroom "created" event.
     * El trait Auditable en el modelo Classroom maneja el registro automáticamente.
     */
    public function created(Classroom $classroom): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Classroom "updated" event.
     * El trait Auditable en el modelo Classroom maneja el registro automáticamente.
     */
    public function updated(Classroom $classroom): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Classroom "deleted" event.
     * El trait Auditable en el modelo Classroom maneja el registro automáticamente.
     */
    public function deleted(Classroom $classroom): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Classroom "restored" event.
     * El trait Auditable en el modelo Classroom maneja el registro automáticamente.
     */
    public function restored(Classroom $classroom): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Classroom "force deleted" event.
     */
    public function forceDeleted(Classroom $classroom): void
    {
        // Auditable trait handles this
    }
}
