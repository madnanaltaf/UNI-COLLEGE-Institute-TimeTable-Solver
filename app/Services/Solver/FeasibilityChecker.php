<?php

namespace App\Services\Solver;

use App\Models\InstitutionSetting;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Classroom;
use App\Models\LessonAllocation;

class FeasibilityChecker
{
    public function check(): array
    {
        $setting = InstitutionSetting::getActive();
        $totalWeeklySlots = $setting->getTotalWeeklySlots();
        $workingDays = $setting->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $issues = [];
        $stats = [
            'total_weekly_slots' => $totalWeeklySlots,
            'sections_count' => Section::count(),
            'teachers_count' => Teacher::count(),
            'allocations_count' => LessonAllocation::count(),
            'rooms_count' => Classroom::count(),
            'total_lesson_periods' => (int) LessonAllocation::sum('periods_per_week'),
            'can_solve' => true,
        ];

        // 1. Check Section Capacities & Over-allocation
        $sections = Section::with('lessonAllocations.subject')->get();
        foreach ($sections as $section) {
            $allotted = $section->lessonAllocations->sum('periods_per_week');
            if ($allotted > $totalWeeklySlots) {
                $issues[] = [
                    'type' => 'CRITICAL',
                    'entity' => 'Section',
                    'name' => $section->name,
                    'message' => "Section {$section->name} is over-allocated: requires {$allotted} periods, but only {$totalWeeklySlots} weekly slots exist.",
                ];
                $stats['can_solve'] = false;
            } elseif ($allotted === $totalWeeklySlots) {
                $issues[] = [
                    'type' => 'INFO',
                    'entity' => 'Section',
                    'name' => $section->name,
                    'message' => "Section {$section->name} has zero free periods ({$allotted}/{$totalWeeklySlots} allotted).",
                ];
            } else {
                $freeSlots = $totalWeeklySlots - $allotted;
                $issues[] = [
                    'type' => 'INFO',
                    'entity' => 'Section',
                    'name' => $section->name,
                    'message' => "Section {$section->name}: {$allotted}/{$totalWeeklySlots} periods allotted ({$freeSlots} free study periods).",
                ];
            }
        }

        // 2. Check Teacher Workloads & Availability Slots
        $teachers = Teacher::with('lessonAllocations')->get();
        foreach ($teachers as $teacher) {
            $assignedPeriods = $teacher->lessonAllocations->sum('periods_per_week');
            $maxWeek = $teacher->max_periods_per_week ?: 24;

            // Calculate total available slots considering teacher's unavailable_slots
            $blockedCount = 0;
            $unavail = $teacher->unavailable_slots ?? [];
            foreach ($unavail as $u) {
                if (is_array($u) && count($u) >= 2) {
                    $day = ucfirst(strtolower($u[0]));
                    $period = (int)$u[1];
                    $dayLimit = $setting->getPeriodsForDay($day);
                    if (in_array($day, $workingDays) && $period <= $dayLimit) {
                        $blockedCount++;
                    }
                }
            }
            $availableWorkingSlots = $totalWeeklySlots - $blockedCount;

            if ($assignedPeriods > $availableWorkingSlots) {
                $issues[] = [
                    'type' => 'CRITICAL',
                    'entity' => 'Teacher',
                    'name' => "{$teacher->name} ({$teacher->code})",
                    'message' => "Teacher Overbooked: {$teacher->name} assigned {$assignedPeriods} periods, but only {$availableWorkingSlots} available working slots exist after blocked times.",
                ];
                $stats['can_solve'] = false;
            } elseif ($assignedPeriods > $maxWeek) {
                $issues[] = [
                    'type' => 'WARNING',
                    'entity' => 'Teacher',
                    'name' => "{$teacher->name} ({$teacher->code})",
                    'message' => "Teacher Load Advisory: {$teacher->name} assigned {$assignedPeriods} periods, exceeding nominal weekly policy of {$maxWeek} periods.",
                ];
            }
        }

        // 3. Check Specialized Room Capacities
        $rooms = Classroom::all();
        $roomDemand = [];
        $allocations = LessonAllocation::with('room')->get();
        foreach ($allocations as $alloc) {
            if ($alloc->room_id) {
                $roomDemand[$alloc->room_id] = ($roomDemand[$alloc->room_id] ?? 0) + $alloc->periods_per_week;
            }
        }

        foreach ($rooms as $room) {
            $demand = $roomDemand[$room->id] ?? 0;
            if ($demand > $totalWeeklySlots) {
                $issues[] = [
                    'type' => 'CRITICAL',
                    'entity' => 'Room',
                    'name' => $room->name,
                    'message' => "Room Overbooked: {$room->name} ({$room->room_type}) demanded for {$demand} periods, but weekly room capacity is {$totalWeeklySlots} periods.",
                ];
                $stats['can_solve'] = false;
            }
        }

        return [
            'stats' => $stats,
            'issues' => $issues,
            'critical_count' => count(array_filter($issues, fn($i) => $i['type'] === 'CRITICAL')),
            'warning_count' => count(array_filter($issues, fn($i) => $i['type'] === 'WARNING')),
            'info_count' => count(array_filter($issues, fn($i) => $i['type'] === 'INFO')),
        ];
    }
}
