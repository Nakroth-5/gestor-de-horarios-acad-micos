<?php

namespace App;

use App\Models\AuditLog;

trait Auditable
{
    /**
     * Boot the auditable trait for a model.
     */
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            $model->auditCreated();
        });

        static::updated(function ($model) {
            $model->auditUpdated();
        });

        static::deleted(function ($model) {
            $model->auditDeleted();
        });
    }

    /**
     * Log when a model is created
     */
    protected function auditCreated()
    {
        $this->logAudit('created', [
            'after' => $this->getAuditableAttributes(),
        ]);
    }

    /**
     * Log when a model is updated
     */
    protected function auditUpdated()
    {
        $changes = $this->getChanges();
        
        if (empty($changes)) {
            return;
        }

        $original = [];
        foreach (array_keys($changes) as $key) {
            $original[$key] = $this->getOriginal($key);
        }

        $this->logAudit('updated', [
            'before' => $original,
            'after' => $changes,
        ]);
    }

    /**
     * Log when a model is deleted
     */
    protected function auditDeleted()
    {
        $this->logAudit('deleted', [
            'before' => $this->getAuditableAttributes(),
        ]);
    }

    /**
     * Create the audit log entry
     */
    protected function logAudit(string $action, array $changes)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'affected_model' => get_class($this),
            'affected_model_id' => $this->id,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get the attributes that should be audited
     */
    protected function getAuditableAttributes(): array
    {
        $attributes = $this->getAttributes();
        
        // Excluir campos sensibles o innecesarios
        $excluded = ['password', 'remember_token', 'created_at', 'updated_at'];
        
        return array_diff_key($attributes, array_flip($excluded));
    }

    /**
     * Manual audit logging
     */
    protected function logAction(string $action, string $message = null, array $extraData = []): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $message ?? $action,
            'affected_model' => get_class($this),
            'affected_model_id' => $this->id,
            'changes' => array_merge([
                'action' => $action,
            ], $extraData),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
