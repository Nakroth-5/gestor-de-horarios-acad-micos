<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_subject_id',
        'subject_id',
        'group_id',
        'day_schedule_id',
        'classroom_id',
        'academic_management_id',
    ];

    protected $with = ['userSubject', 'group', 'daySchedule', 'classroom'];

    /**
     * Boot method para llenar automáticamente subject_id
     */
    protected static function boot(): void
    {
        parent::boot();

        // Antes de crear, llenar subject_id desde user_subjects
        static::creating(function ($assignment) {
            if ($assignment->user_subject_id && !$assignment->subject_id) {
                $userSubject = UserSubject::find($assignment->user_subject_id);
                if ($userSubject) {
                    $assignment->subject_id = $userSubject->subject_id;
                }
            }
        });

        // Antes de actualizar, llenar subject_id si cambió user_subject_id
        static::updating(function ($assignment) {
            if ($assignment->isDirty('user_subject_id')) {
                $userSubject = UserSubject::find($assignment->user_subject_id);
                if ($userSubject) {
                    $assignment->subject_id = $userSubject->subject_id;
                }
            }
        });
    }

    /**
     * Relación con user_subjects (docente-materia)
     */
    public function userSubject(): BelongsTo
    {
        return $this->belongsTo(UserSubject::class);
    }

    /**
     * Relación con subject (redundante pero útil para queries)
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relación con group
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Relación con day_schedule
     */
    public function daySchedule(): BelongsTo
    {
        return $this->belongsTo(DaySchedule::class);
    }

    /**
     * Relación con classroom
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Relación con academic_management
     */
    public function academicManagement(): BelongsTo
    {
        return $this->belongsTo(AcademicManagement::class);
    }

    /**
     * Obtener el usuario (docente) a través de userSubject
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            UserSubject::class,
            'id', // Foreign key en user_subjects
            'id', // Foreign key en users
            'user_subject_id', // Local key en assignments
            'user_id' // Local key en user_subjects
        );
    }

    /**
     * Scope por periodo académico
     */
    public function scopeByAcademicManagement($query, $academicManagementId)
    {
        return $query->where('academic_management_id', $academicManagementId);
    }

    /**
     * Scope por grupo
     */
    public function scopeByGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    /**
     * Scope por docente
     */
    public function scopeByTeacher($query, $userId)
    {
        return $query->whereHas('userSubject', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Verificar si existe conflicto de horario
     */
    public static function hasClassroomConflict(
        $classroomId,
        $dayScheduleId,
        $academicManagementId,
        $excludeId = null
    ) {
        return self::where('classroom_id', $classroomId)
            ->where('day_schedule_id', $dayScheduleId)
            ->where('academic_management_id', $academicManagementId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * Verificar si existe conflicto de materia-grupo
     */
    public static function hasSubjectGroupConflict(
        $subjectId,
        $groupId,
        $academicManagementId,
        $excludeId = null
    ) {
        return self::where('subject_id', $subjectId)
            ->where('group_id', $groupId)
            ->where('academic_management_id', $academicManagementId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * Obtener horario de un grupo
     */
    public static function getGroupSchedule($groupId, $academicManagementId = null)
    {
        return self::with([
            'userSubject.user',
            'userSubject.subject',
            'classroom',
            'daySchedule.day',
            'daySchedule.schedule'
        ])
            ->where('group_id', $groupId)
            ->when($academicManagementId, fn($q) => $q->where('academic_management_id', $academicManagementId))
            ->get()
            ->groupBy(function ($assignment) {
                return $assignment->daySchedule->day->name ?? 'Sin día';
            });
    }
}
