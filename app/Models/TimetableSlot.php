<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimetableSlot extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'period' => 'integer',
        'is_locked' => 'boolean',
        'is_combined' => 'boolean',
    ];

    public function isCombined(): bool
    {
        return (bool) $this->is_combined;
    }

    public function getSiblingCombinedSlots()
    {
        if ($this->combined_group_id) {
            return static::where('combined_group_id', $this->combined_group_id)
                ->where('day', $this->day)
                ->where('period', $this->period)
                ->with(['section', 'teacher', 'subject', 'room'])
                ->get();
        }

        // Fallback: Check if other sections of the same grade class share the same teacher, subject, room, day, and period
        if ($this->section && $this->section->grade_class_id) {
            $classSecIds = Section::where('grade_class_id', $this->section->grade_class_id)->pluck('id');
            return static::whereIn('section_id', $classSecIds)
                ->where('teacher_id', $this->teacher_id)
                ->where('day', $this->day)
                ->where('period', $this->period)
                ->with(['section', 'teacher', 'subject', 'room'])
                ->get();
        }

        return collect([$this]);
    }

    public function lessonAllocation(): BelongsTo
    {
        return $this->belongsTo(LessonAllocation::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
