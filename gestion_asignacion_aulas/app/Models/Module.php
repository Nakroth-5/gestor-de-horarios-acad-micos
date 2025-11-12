<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use Auditable;

    protected $fillable = [
        'code',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['name'];

    // Relationships
    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    // Attributes
    public function getNameAttribute(): string
    {
        return 'Módulo ' . $this->code;
    }
}
