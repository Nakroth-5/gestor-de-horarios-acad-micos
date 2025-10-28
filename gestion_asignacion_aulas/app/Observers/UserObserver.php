<?php

namespace App\Observers;

use App\Auditable;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    use Auditable;
    public function created(User $user): void
    {
        $this->logAction('created', $user, "A creado a { $user->name }");
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->logAction('updated', $user, "Actualizo a { $user->name  }");
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->logAction('deleted', $user, "Eliminada a { $user->name }");
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->logAction('restored', $user, "Restauro a { $user->name }");
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(): void
    {
    }
}
