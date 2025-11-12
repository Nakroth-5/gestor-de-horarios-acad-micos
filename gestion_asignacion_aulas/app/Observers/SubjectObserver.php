<?php

namespace App\Observers;

use App\Models\Subject;

class SubjectObserver
{
    /**
     * Handle the Subject "created" event.
     * El trait Auditable en el modelo Subject maneja el registro automáticamente.
     */
    public function created(Subject $subject): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Subject "updated" event.
     * El trait Auditable en el modelo Subject maneja el registro automáticamente.
     */
    public function updated(Subject $subject): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Subject "deleted" event.
     * El trait Auditable en el modelo Subject maneja el registro automáticamente.
     */
    public function deleted(Subject $subject): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Subject "restored" event.
     * El trait Auditable en el modelo Subject maneja el registro automáticamente.
     */
    public function restored(Subject $subject): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Subject "force deleted" event.
     */
    public function forceDeleted(Subject $subject): void
    {
        // Auditable trait handles this
    }
}
