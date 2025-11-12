<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_type',
        'priority',
        'is_automatic',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'is_automatic' => 'boolean',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para notificaciones no leídas
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope para notificaciones automáticas
     */
    public function scopeAutomatic($query)
    {
        return $query->where('is_automatic', true);
    }

    /**
     * Scope para notificaciones manuales
     */
    public function scopeManual($query)
    {
        return $query->where('is_automatic', false);
    }

    /**
     * Scope para notificaciones por prioridad
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Marcar como leída
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Verificar si está leída
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Obtener el ícono según el tipo
     */
    public function getIconAttribute(): string
    {
        return match($this->notification_type) {
            'attendance_pending' => 'clipboard',
            'new_subject' => 'book',
            'schedule_change' => 'refresh',
            'direct_message' => 'mail',
            'reservation_approved' => 'check',
            'reservation_rejected' => 'x',
            'reservation_permission' => 'bell',
            default => 'pencil'
        };
    }

    /**
     * Obtener el emoji según el tipo
     */
    public function getEmojiAttribute(): string
    {
        return match($this->notification_type) {
            'attendance_pending' => '📋',
            'new_subject' => '📚',
            'schedule_change' => '🔄',
            'direct_message' => '✉️',
            'reservation_approved' => '✅',
            'reservation_rejected' => '❌',
            'reservation_permission' => '🔔',
            default => '✏️'
        };
    }

    /**
     * Obtener el color según la prioridad
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'red',
            'important' => 'yellow',
            'info' => 'blue',
            default => 'gray'
        };
    }

    /**
     * Obtener el color del borde según si está leída
     */
    public function getBorderColorAttribute(): string
    {
        if ($this->read_at) {
            return 'border-gray-300 dark:border-gray-700 opacity-70';
        }

        return match($this->priority) {
            'urgent' => 'border-red-500',
            'important' => 'border-yellow-500',
            'info' => 'border-blue-600',
            default => 'border-blue-600'
        };
    }
}