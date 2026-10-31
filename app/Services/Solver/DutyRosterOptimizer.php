<?php

namespace App\Services\Solver;

use App\Models\DutySlot;
use App\Models\DutyAssignment;
use App\Models\Teacher;
use App\Models\TimetableSlot;
use App\Models\InstitutionSetting;
use Illuminate\Support\Facades\DB;

class DutyRosterOptimizer
{
    protected InstitutionSetting $setting;

    public function __construct(?InstitutionSetting $setting = null)
    {
        $this->setting = $setting ?? InstitutionSetting::getActive();
    }

    public function optimize(): array
    {
        // 1. Fetch all duty slots with their duty types
        $dutySlots = DutySlot::with('dutyType')->orderBy('day')->orderBy('period')->get();
        if ($dutySlots->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No duty slots configured in system.',
                'assigned_count' => 0,
            ];
        }

        $teachers = Teacher::all();
        if ($teachers->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No teachers found.',
                'assigned_count' => 0,
            ];
        }

        // 2. Build teacher teaching occupancy index: [teacher_id][day][period] = true
        $teachingSlots = TimetableSlot::all();
        $teacherBusy = [];
        foreach ($teachingSlots as $ts) {
            $teacherBusy[$ts->teacher_id][$ts->day][$ts->period] = true;
        }

        // 3. Track duty load per teacher for fair-share distribution
        $dutyLoad = [];
        $teacherDailyDuty = []; // [teacher_id][day] = count
        foreach ($teachers as $t) {
            $dutyLoad[$t->id] = 0;
            $teacherDailyDuty[$t->id] = [];
        }

        $assignmentsToInsert = [];
        $breakAfterPeriod = $this->setting->break_after_period ?? 4;

        foreach ($dutySlots as $slot) {
            $needed = $slot->required_teachers ?: 2;
            $day = $slot->day;
            $period = $slot->period; // 0 = Morning Assembly, -1 = Gate Duty, 99 = Recess

            // Candidate teachers:
            // - Not teaching during this period (if period > 0; or if recess: period 99 -> recess occurs right after break_after_period, teachers shouldn't be overloaded)
            // - Sort candidates by lowest cumulative duty load (fair-share)
            $candidates = [];

            foreach ($teachers as $t) {
                // Check if teacher has an active class during this period
                if ($period > 0 && $period <= 8 && !empty($teacherBusy[$t->id][$day][$period])) {
                    continue; // In class, cannot do duty
                }

                // If teacher is blocked on this day/period
                if (!$t->isAvailable($day, $period > 0 ? $period : 1)) {
                    continue;
                }

                // Discourage multiple duties on the same day for the same teacher
                $dailyCount = $teacherDailyDuty[$t->id][$day] ?? 0;
                $effectiveScore = ($dutyLoad[$t->id] * 10) + ($dailyCount * 50);

                $candidates[] = [
                    'teacher' => $t,
                    'score' => $effectiveScore,
                    'current_load' => $dutyLoad[$t->id],
                ];
            }

            // Sort by lowest load
            usort($candidates, fn($a, $b) => $a['score'] <=> $b['score']);

            $assignedForThisSlot = 0;
            foreach ($candidates as $cand) {
                if ($assignedForThisSlot >= $needed) {
                    break;
                }
                $t = $cand['teacher'];
                $assignmentsToInsert[] = [
                    'duty_slot_id' => $slot->id,
                    'teacher_id' => $t->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $dutyLoad[$t->id]++;
                $teacherDailyDuty[$t->id][$day] = ($teacherDailyDuty[$t->id][$day] ?? 0) + 1;
                $assignedForThisSlot++;
            }
        }

        // Persist duty assignments
        DB::transaction(function () use ($assignmentsToInsert) {
            DutyAssignment::query()->delete();
            foreach (array_chunk($assignmentsToInsert, 100) as $chunk) {
                DutyAssignment::insert($chunk);
            }
        });

        // Compute fair-share distribution metrics
        $loads = array_values($dutyLoad);
        $minLoad = !empty($loads) ? min($loads) : 0;
        $maxLoad = !empty($loads) ? max($loads) : 0;
        $spread = $maxLoad - $minLoad;

        return [
            'success' => true,
            'message' => "Fair-share duty roster successfully generated ({$spread} duty spread across faculty).",
            'total_assigned' => count($assignmentsToInsert),
            'min_load' => $minLoad,
            'max_load' => $maxLoad,
            'spread' => $spread,
            'duty_distribution' => $dutyLoad,
        ];
    }
}
