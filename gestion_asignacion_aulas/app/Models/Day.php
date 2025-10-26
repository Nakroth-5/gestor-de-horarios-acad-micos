<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Day extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relationships
    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'day_schedules');
    }

    public function daySchedules(): HasMany
    {
        return $this->hasMany(DaySchedule::class);
    }
}
