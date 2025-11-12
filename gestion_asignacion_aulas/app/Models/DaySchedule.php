<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DaySchedule extends Model
{
    use Auditable;
    
    protected $fillable = [
        'day_id',
        'schedule_id',
    ];

    // Relationships
    public function day(): BelongsTo
    {
        return $this->belongsTo(Day::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    // Helper methods
    public function getFormattedSchedule(): string
    {
        return $this->day->name . ' ' .
            $this->schedule->start->format('H:i') . '-' .
            $this->schedule->end->format('H:i');
    }
}
