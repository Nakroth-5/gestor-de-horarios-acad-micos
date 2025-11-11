<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Assignment;
use App\Models\User;
use App\Models\QrToken;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'user_id',
        'scan_time',
        'finish_time',
        'status',
    ];

    protected $casts = [
        'scan_time' => 'datetime',
        'finish_time' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'absent',
    ];

    /**
     * Boot method para establecer valores por defecto
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attendanceRecord) {
            if (is_null($attendanceRecord->scan_time)) {
                $attendanceRecord->scan_time = null;
            }
        });
    }

    // Relación con la asignación (clase recurrente)
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    // Relación con el docente/usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con QR tokens (1:1)
    public function qrToken()
    {
        return $this->hasOne(QrToken::class, 'attendance_id');
    }

    // Relación alternativa hasMany para compatibilidad
    public function qrTokens()
    {
        return $this->hasMany(QrToken::class, 'attendance_id');
    }

    /**
     * Scope para filtrar por asignación
     */
    public function scopeForAssignment($query, $assignmentId)
    {
        return $query->where('assignment_id', $assignmentId);
    }

    /**
     * Scope para filtrar por usuario (docente)
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por semana actual
     */
    public function scopeCurrentWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Verificar si la asistencia fue marcada
     */
    public function isMarked(): bool
    {
        return !is_null($this->scan_time) && in_array($this->status, ['on_time', 'late']);
    }

    /**
     * Obtener badge de color según estado
     */
    public function getStatusBadge(): string
    {
        return match($this->status) {
            'on_time' => '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200 rounded-full">A tiempo</span>',
            'late' => '<span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">Atrasado</span>',
            'absent' => '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-200 rounded-full">Ausente</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-full">Sin marcar</span>',
        };
    }
}
