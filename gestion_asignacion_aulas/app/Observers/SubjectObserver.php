<?php

namespace App\Observers;

use App\Auditable;
use App\Models\Subject;

class SubjectObserver
{
    use Auditable;
    /**
     * Handle the Subject "created" event.
     */
    public function created(Subject $subject): void
    {
        $this->logAction('created', $subject, "A creado la materia { $subject->name }");
    }

    /**
     * Handle the Subject "updated" event.
     */
    public function updated(Subject $subject): void
    {
        $this->logAction('update', $subject, "A actualizado la materia { $subject->name }");
    }

    /**
     * Handle the Subject "deleted" event.
     */
    public function deleted(Subject $subject): void
    {
        $this->logAction('delete', $subject, "A eliminado la materia { $subject->name }");
    }

    /**
     * Handle the Subject "restored" event.
     */
    public function restored(Subject $subject): void
    {
        //
    }

    /**
     * Handle the Subject "force deleted" event.
     */
    public function forceDeleted(Subject $subject): void
    {
        //
    }
}
