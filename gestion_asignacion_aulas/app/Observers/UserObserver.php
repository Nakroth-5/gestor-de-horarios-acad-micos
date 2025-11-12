<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * El trait Auditable en el modelo User maneja el registro automáticamente.
     */
    public function created(User $user): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the User "updated" event.
     * El trait Auditable en el modelo User maneja el registro automáticamente.
     */
    public function updated(User $user): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the User "deleted" event.
     * El trait Auditable en el modelo User maneja el registro automáticamente.
     */
    public function deleted(User $user): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the User "restored" event.
     * El trait Auditable en el modelo User maneja el registro automáticamente.
     */
    public function restored(User $user): void
    {
        // Auditable trait handles this
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        // Auditable trait handles this
    }
}
