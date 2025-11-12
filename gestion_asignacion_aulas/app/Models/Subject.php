<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use Auditable;
    protected $fillable = [
        'code',
        'name',
        'credits',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_subjects');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function universityCareers()
    {
        return $this->belongsToMany(UniversityCareer::class, 'subject_university_career')
            ->withPivot(['semester', 'is_required'])
            ->withTimestamps();
    }
}
