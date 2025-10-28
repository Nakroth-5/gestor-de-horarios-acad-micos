<?php

namespace App\Observers;

use App\Auditable;
use App\Models\Group;

class GroupObserver
{
    use Auditable;
    /**
     * Handle the yes "created" event.
     */
    public function created(Group $group): void
    {
        $this->logAction('created', $group, "A creado al grupo { $group->name }");
    }

    /**
     * Handle the yes "updated" event.
     */
    public function updated(Group $group): void
    {
        $this->logAction('update', $group, "A actualizado al grupo { $group->name }");
    }

    /**
     * Handle the yes "deleted" event.
     */
    public function deleted(Group $group): void
    {
        $this->logAction('delete', $group, "A eliminado al grupo { $group->name }");
    }

    /**
     * Handle the yes "restored" event.
     */
    public function restored(Group $group): void
    {
        //
    }

    /**
     * Handle the yes "force deleted" event.
     */
    public function forceDeleted(Group $group): void
    {
        //
    }
}
