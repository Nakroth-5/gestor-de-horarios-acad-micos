<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use Auditable;
    protected $fillable = [
        'module_id',
        'number',
        'type',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['name'];

    // Relationships
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    // Alias para infrastructure (module es la infraestructura/edificio)
    public function infrastructure(): BelongsTo
    {
        return $this->module();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    // Attributes
    public function getNameAttribute(): string
    {
        return $this->module->code . '-' . $this->number;
    }

    // Helper methods
    public function isAvailable($dayScheduleId): bool
    {
        return !$this->assignments()
            ->where('day_schedule_id', $dayScheduleId)
            ->exists();
    }
}
