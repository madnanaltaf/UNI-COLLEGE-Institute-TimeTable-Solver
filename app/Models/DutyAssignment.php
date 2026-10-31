<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DutyAssignment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(DutySlot::class, 'duty_slot_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
