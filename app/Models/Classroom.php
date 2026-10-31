<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function homeSections(): HasMany
    {
        return $this->hasMany(Section::class, 'home_room_id');
    }

    public function timetableSlots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class, 'room_id');
    }

    public function lessonAllocations(): HasMany
    {
        return $this->hasMany(LessonAllocation::class, 'room_id');
    }
}
