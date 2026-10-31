<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'requires_lab' => 'boolean',
        'is_difficult' => 'boolean',
        'allowed_double_period' => 'boolean',
    ];

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class, 'primary_subject_id');
    }

    public function lessonAllocations(): HasMany
    {
        return $this->hasMany(LessonAllocation::class);
    }

    public function timetableSlots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class);
    }
}
