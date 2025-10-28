<?php

namespace App;

use App\Models\AuditLog;

trait Auditable
{
    protected function logAction(string $action, $model, string $message = null): void
    {
        $actor = auth()->user();

        $original = $model->getOriginal();
        $changes  = $model->getChanges();

        AuditLog::create([
            'user_id' => $actor?->id,
            'action' => $message ?? $action,
            'changes' => [
                'before' => $original,
                'after' => $changes,
            ],
            'affected_model' => get_class($model),
            'affected_model_id' => $model->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
