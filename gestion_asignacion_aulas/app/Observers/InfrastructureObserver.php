<?php

namespace App\Observers;

use App\Models\Module;

class InfrastructureObserver
{
    /**
     * Handle the Module "created" event.
     * El trait Auditable en el modelo Module maneja el registro automáticamente.
     */
    public function created(Module $module): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Module "updated" event.
     * El trait Auditable en el modelo Module maneja el registro automáticamente.
     */
    public function updated(Module $module): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Module "deleted" event.
     * El trait Auditable en el modelo Module maneja el registro automáticamente.
     */
    public function deleted(Module $module): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Module "restored" event.
     * El trait Auditable en el modelo Module maneja el registro automáticamente.
     */
    public function restored(Module $module): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Module "force deleted" event.
     */
    public function forceDeleted(Module $module): void
    {
        // Auditable trait handles this
    }
}
