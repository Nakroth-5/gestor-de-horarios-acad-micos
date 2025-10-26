<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [
        'day_schedule_id',
        'subject_id',
        'classroom_id',
        'user_id',
    ];

    // Relationships
    public function daySchedule(): BelongsTo
    {
        return $this->belongsTo(DaySchedule::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getFullScheduleInfo(): array
    {
        return [
            'teacher' => $this->user->name . ' ' . $this->user->last_name,
            'subject' => $this->subject->name,
            'classroom' => 'Aula ' . $this->classroom->number . ' - Módulo ' . $this->classroom->module->code,
            'schedule' => $this->daySchedule->getFormattedSchedule(),
        ];
    }
}
