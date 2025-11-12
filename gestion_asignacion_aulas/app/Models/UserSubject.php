<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserSubject extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'subject_id',
        'university_career_id',
    ];

    /**
     * Relación con User (docente)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con Subject (materia)
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relación con UniversityCareer (carrera)
     */
    public function universityCareer(): BelongsTo
    {
        return $this->belongsTo(UniversityCareer::class);
    }

    /**
     * Relación con Assignments
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Scope para filtrar por docente
     */
    public function scopeByTeacher($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por materia
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Obtener todas las asignaciones de este docente-materia
     */
    public function getAssignmentsWithDetails()
    {
        return $this->assignments()
            ->with(['group', 'daySchedule', 'classroom', 'academicManagement'])
            ->get();
    }
}
