<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AttendanceRecord;

class QrToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'token',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    // Relación con el registro de asistencia (1:1)
    public function attendance()
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_id');
    }

    // Alias para compatibilidad
    public function attendanceRecord()
    {
        return $this->attendance();
    }

    /**
     * Verificar si el token expiró
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Verificar si el token es válido (no expirado y no usado)
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->used;
    }

    /**
     * Marcar el token como usado
     */
    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }

    /**
     * Scope para tokens válidos
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
                     ->where('used', false);
    }

    /**
     * Scope para tokens expirados
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }
}
