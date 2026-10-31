<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'student_count' => 'integer',
    ];

    public function gradeClass(): BelongsTo
    {
        return $this->belongsTo(GradeClass::class);
    }

    public function homeRoom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'home_room_id');
    }

    public function classTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'class_teacher_id');
    }

    public function lessonAllocations(): HasMany
    {
        return $this->hasMany(LessonAllocation::class);
    }

    public function timetableSlots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class);
    }

    public function getTotalAllottedPeriodsAttribute(): int
    {
        return (int) $this->lessonAllocations()->sum('periods_per_week');
    }
}
