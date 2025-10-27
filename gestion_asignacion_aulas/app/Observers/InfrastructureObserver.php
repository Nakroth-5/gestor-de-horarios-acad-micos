<?php

namespace App\Observers;

use App\Auditable;
use App\Models\Module;

class InfrastructureObserver
{
    /**
     * Handle the Module "created" event.
     */
    use Auditable;
    public function created(Module $module): void
    {
        $this->logAction('created', $module, "A creado al modulo { $module->code }");
    }

    /**
     * Handle the Module "updated" event.
     */
    public function updated(Module $module): void
    {
        $this->logAction('updated', $module, "Actualizo al modulo {  $module->code  }");
    }

    /**
     * Handle the Module "deleted" event.
     */
    public function deleted(Module $module): void
    {
        $this->logAction('deleted', $module, "Eliminada al modulo {  $module->code }");
    }

    /**
     * Handle the Module "restored" event.
     */
    public function restored(Module $module): void
    {
        $this->logAction('restored', $module, "Restauro al modulo {  $module->code }");
    }

    /**
     * Handle the Module "force deleted" event.
     */
    public function forceDeleted(): void
    {
    }
}
