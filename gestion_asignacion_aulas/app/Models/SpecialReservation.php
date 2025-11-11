<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialReservation extends Model
{
    protected $fillable = [
        'classroom_id',
        'user_id',
        'approved_by',
        'event_type',
        'title',
        'description',
        'reservation_date',
        'start_time',
        'end_time',
        'status',
        'rejection_reason',
        'estimated_attendees',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // Relationships
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'aprobada');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rechazada');
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('reservation_date', $date);
    }

    public function scopeByClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }
}
