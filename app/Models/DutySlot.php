<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DutySlot extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'period' => 'integer',
        'required_teachers' => 'integer',
    ];

    public function dutyType(): BelongsTo
    {
        return $this->belongsTo(DutyType::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(DutyAssignment::class);
    }
}
