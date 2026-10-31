<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'qualified_subjects' => 'array',
        'unavailable_slots' => 'array',
        'max_periods_per_week' => 'integer',
        'max_periods_per_day' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($teacher) {
            if (empty($teacher->code) && !empty($teacher->name)) {
                $teacher->code = self::generateInitials($teacher->name);
            }
        });
    }

    public static function generateInitials(string $name): string
    {
        // Strip titles like Dr., Prof., Engr., Mr., Mrs.
        $clean = preg_replace('/^(Dr\.|Prof\.|Engr\.|Mr\.|Mrs\.|Ms\.)\s+/i', '', trim($name));
        $words = preg_split('/[\s\.\-_]+/', $clean, -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) === 1) {
            return strtoupper(substr($words[0], 0, 3));
        }
        $initials = '';
        foreach ($words as $w) {
            $initials .= strtoupper($w[0]);
        }
        return substr($initials, 0, 5);
    }

    public function primarySubject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'primary_subject_id');
    }

    public function inchargeSection(): HasMany
    {
        return $this->hasMany(Section::class, 'class_teacher_id');
    }

    public function lessonAllocations(): HasMany
    {
        return $this->hasMany(LessonAllocation::class);
    }

    public function timetableSlots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class);
    }

    public function dutyAssignments(): HasMany
    {
        return $this->hasMany(DutyAssignment::class);
    }

    public function setSingleSubject(int $subId): void
    {
        $this->primary_subject_id = $subId;
        $this->qualified_subjects = [$subId];
        $this->save();
    }

    public function teachesSubject(int $subId): bool
    {
        if ($this->primary_subject_id === $subId) {
            return true;
        }
        if (is_array($this->qualified_subjects) && in_array($subId, $this->qualified_subjects)) {
            return true;
        }
        return false;
    }

    public function isAvailable(string $day, int $period): bool
    {
        $day = ucfirst(strtolower(trim($day)));
        $unavail = $this->unavailable_slots ?? [];
        foreach ($unavail as $slot) {
            if (is_array($slot) && count($slot) >= 2) {
                if (ucfirst(strtolower(trim($slot[0]))) === $day && (int)$slot[1] === (int)$period) {
                    return false;
                }
            }
        }
        return true;
    }

    public function addUnavailableSlot(string $day, int $period): void
    {
        $day = ucfirst(strtolower(trim($day)));
        $slots = $this->unavailable_slots ?? [];
        foreach ($slots as $slot) {
            if (is_array($slot) && count($slot) >= 2) {
                if (ucfirst(strtolower(trim($slot[0]))) === $day && (int)$slot[1] === (int)$period) {
                    return; // already blocked
                }
            }
        }
        $slots[] = [$day, (int)$period];
        $this->unavailable_slots = array_values($slots);
        $this->save();
    }

    public function removeUnavailableSlot(string $day, int $period): void
    {
        $day = ucfirst(strtolower(trim($day)));
        $slots = $this->unavailable_slots ?? [];
        $newSlots = [];
        foreach ($slots as $slot) {
            if (is_array($slot) && count($slot) >= 2) {
                if (ucfirst(strtolower(trim($slot[0]))) === $day && (int)$slot[1] === (int)$period) {
                    continue;
                }
            }
            $newSlots[] = $slot;
        }
        $this->unavailable_slots = array_values($newSlots);
        $this->save();
    }
}
