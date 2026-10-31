<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonAllocation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'periods_per_week' => 'integer',
        'block_size' => 'integer',
        'is_combined' => 'boolean',
    ];

    public function isCombined(): bool
    {
        return (bool) $this->is_combined;
    }

    public function getSiblingAllocations()
    {
        if (!$this->combined_group_id) {
            return collect([$this]);
        }
        return static::where('combined_group_id', $this->combined_group_id)->get();
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function timetableSlots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class);
    }
}
