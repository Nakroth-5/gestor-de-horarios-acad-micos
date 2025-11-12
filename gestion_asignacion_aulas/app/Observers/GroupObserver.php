<?php

namespace App\Observers;

use App\Models\Group;

class GroupObserver
{
    /**
     * Handle the Group "created" event.
     * El trait Auditable en el modelo Group maneja el registro automáticamente.
     */
    public function created(Group $group): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Group "updated" event.
     * El trait Auditable en el modelo Group maneja el registro automáticamente.
     */
    public function updated(Group $group): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Group "deleted" event.
     * El trait Auditable en el modelo Group maneja el registro automáticamente.
     */
    public function deleted(Group $group): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Group "restored" event.
     * El trait Auditable en el modelo Group maneja el registro automáticamente.
     */
    public function restored(Group $group): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Group "force deleted" event.
     */
    public function forceDeleted(Group $group): void
    {
        // Auditable trait handles this
    }
}
