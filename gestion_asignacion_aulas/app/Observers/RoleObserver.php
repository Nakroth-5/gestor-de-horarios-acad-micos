<?php

namespace App\Observers;

use App\Auditable;
use App\Models\Role;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    use Auditable;
    public function created(Role $role): void
    {
        $this->logAction('created', $role, "A creado al rol { $role->name }");
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        $this->logAction('updated', $role, "Actualizo al rol {  $role->name  }");
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        $this->logAction('deleted', $role, "Eliminada al rol {  $role->name }");
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        $this->logAction('restored', $role, "Restauro al rol {  $role->name }");
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(): void
    {
    }
}
