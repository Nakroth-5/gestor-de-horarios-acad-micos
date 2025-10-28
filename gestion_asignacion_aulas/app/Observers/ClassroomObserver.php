<?php

namespace App\Observers;

use App\Auditable;
use App\Models\Classroom;

class ClassroomObserver
{
    /**
     * Handle the Classroom "created" event.
     */
    use Auditable;
    public function created(Classroom $classroom): void
    {
        $this->logAction('created', $classroom, "A creado a la clase { $classroom->number }");
    }

    /**
     * Handle the Classroom "updated" event.
     */
    public function updated(Classroom $classroom): void
    {
        $this->logAction('updated', $classroom, "Actualizo a la clase {  $classroom->number  }");
    }

    /**
     * Handle the Classroom "deleted" event.
     */
    public function deleted(Classroom $classroom): void
    {
        $this->logAction('deleted', $classroom, "Eliminada a {  $classroom->number }");
    }

    /**
     * Handle the Classroom "restored" event.
     */
    public function restored(Classroom $classroom): void
    {
        $this->logAction('restored', $classroom, "Restauro a {  $classroom->number }");
    }

    /**
     * Handle the Classroom "force deleted" event.
     */
    public function forceDeleted(): void
    {
    }
}
