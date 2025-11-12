<?php

namespace App\Observers;

use App\Models\Role;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     * El trait Auditable en el modelo Role maneja el registro automáticamente.
     */
    public function created(Role $role): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Role "updated" event.
     * El trait Auditable en el modelo Role maneja el registro automáticamente.
     */
    public function updated(Role $role): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Role "deleted" event.
     * El trait Auditable en el modelo Role maneja el registro automáticamente.
     */
    public function deleted(Role $role): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Role "restored" event.
     * El trait Auditable en el modelo Role maneja el registro automáticamente.
     */
    public function restored(Role $role): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        // Auditable trait handles this
    }
}
