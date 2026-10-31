<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'working_days' => 'array',
        'day_periods' => 'array',
        'periods_per_day' => 'integer',
        'friday_periods' => 'integer',
        'saturday_periods' => 'integer',
        'break_after_period' => 'integer',
        'break_duration_mins' => 'integer',
        'period_duration_mins' => 'integer',
        'start_hour' => 'integer',
        'start_min' => 'integer',
    ];

    public static function getActive(): self
    {
        return self::firstOrCreate([], [
            'name' => 'Government Higher Secondary School & College',
            'academic_year' => '2026-2027',
            'term' => 'Annual Term',
            'motto' => 'Excellence in Education & Character',
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'periods_per_day' => 6,
            'friday_periods' => 4,
            'day_periods' => [
                'Monday' => 6,
                'Tuesday' => 6,
                'Wednesday' => 6,
                'Thursday' => 6,
                'Friday' => 4,
                'Saturday' => 6,
            ],
            'break_after_period' => 3,
            'break_duration_mins' => 30,
            'period_duration_mins' => 45,
            'start_hour' => 8,
            'start_min' => 0,
        ]);
    }

    public function getPeriodsForDay(string $day): int
    {
        $day = ucfirst(strtolower(trim($day)));
        if (!empty($this->day_periods) && isset($this->day_periods[$day])) {
            return (int) $this->day_periods[$day];
        }
        if ($day === 'Friday') {
            return (int) ($this->friday_periods ?? 4);
        }
        if ($day === 'Saturday') {
            return (int) ($this->saturday_periods ?? 6);
        }
        return (int) ($this->periods_per_day ?? 7);
    }

    public function getMaxPeriodsAnyDay(): int
    {
        $max = (int) $this->periods_per_day;
        if (!empty($this->day_periods)) {
            foreach ($this->day_periods as $p) {
                if ((int)$p > $max) {
                    $max = (int)$p;
                }
            }
        }
        return $max;
    }

    public function getTotalWeeklySlots(): int
    {
        $total = 0;
        $days = $this->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $day) {
            $total += $this->getPeriodsForDay($day);
        }
        return $total;
    }

    public function generateDefaultPeriodTimes(): array
    {
        $times = [];
        $maxPeriods = $this->getMaxPeriodsAnyDay();
        $currHour = $this->start_hour ?? 8;
        $currMin = $this->start_min ?? 0;
        $periodDur = $this->period_duration_mins ?? 45;
        $breakDur = $this->break_duration_mins ?? 30;
        $breakAfter = $this->break_after_period ?? 4;

        $totalMinutes = ($currHour * 60) + $currMin;

        for ($p = 1; $p <= $maxPeriods; $p++) {
            $startM = $totalMinutes;
            $endM = $totalMinutes + $periodDur;

            $sH = intdiv($startM, 60);
            $sM = $startM % 60;
            $eH = intdiv($endM, 60);
            $eM = $endM % 60;

            $times[$p] = [
                'period' => $p,
                'start_formatted' => sprintf('%02d:%02d', $sH, $sM),
                'end_formatted' => sprintf('%02d:%02d', $eH, $eM),
                'display' => sprintf('%02d:%02d - %02d:%02d', $sH, $sM, $eH, $eM),
            ];

            $totalMinutes = $endM;

            if ($p === $breakAfter) {
                $totalMinutes += $breakDur;
            }
        }

        return $times;
    }

    public function toArrayJson(): array
    {
        return $this->toArray();
    }
}
