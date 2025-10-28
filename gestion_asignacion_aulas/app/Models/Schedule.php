<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'start',
        'end',
    ];

    //Relationships
    public function daySchedules(): HasMany
    {
        return $this->hasMany(DaySchedule::class);
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Day::class, 'day_schedules');
    }
}
