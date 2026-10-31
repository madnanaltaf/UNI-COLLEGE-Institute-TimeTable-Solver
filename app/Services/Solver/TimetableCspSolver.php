<?php

namespace App\Services\Solver;

use App\Models\InstitutionSetting;
use App\Models\LessonAllocation;
use App\Models\TimetableSlot;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;

class TimetableCspSolver
{
    protected InstitutionSetting $setting;
    protected array $workingDays = [];
    protected array $dayPeriodLimits = [];
    protected int $breakAfterPeriod = 4;
    protected array $teacherUnavailable = []; // [teacher_id => ['Monday_1' => true, ...]]

    // State occupancy grids
    protected array $teacherGrid = [];
    protected array $sectionGrid = [];
    protected array $roomGrid = [];
    protected array $sectionSubDayCount = [];
    protected array $teacherDayCount = [];

    protected array $assignments = []; // [task_id => ['day' => ..., 'period' => ..., 'task' => ...]]
    protected float $startTime = 0.0;
    protected float $solveDurationMs = 0.0;
    protected int $iterations = 0;

    public function __construct(?InstitutionSetting $setting = null)
    {
        $this->setting = $setting ?? InstitutionSetting::getActive();
        $this->workingDays = $this->setting->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($this->workingDays as $day) {
            $this->dayPeriodLimits[$day] = $this->setting->getPeriodsForDay($day);
        }
        $this->breakAfterPeriod = (int) ($this->setting->break_after_period ?? 4);

        $teachers = Teacher::all();
        foreach ($teachers as $t) {
            $this->teacherUnavailable[$t->id] = [];
            foreach ($t->unavailable_slots ?? [] as $u) {
                if (is_array($u) && count($u) >= 2) {
                    $k = ucfirst(strtolower($u[0])) . '_' . (int)$u[1];
                    $this->teacherUnavailable[$t->id][$k] = true;
                }
            }
        }
    }

    public function solve(bool $persist = true): array
    {
        $this->startTime = microtime(true);
        $this->iterations = 0;
        $this->assignments = [];
        $this->teacherGrid = [];
        $this->sectionGrid = [];
        $this->roomGrid = [];
        $this->sectionSubDayCount = [];
        $this->teacherDayCount = [];

        // Check if there are pre-locked slots in DB and occupy them
        $lockedSlots = TimetableSlot::where('is_locked', true)->get();
        foreach ($lockedSlots as $slot) {
            $this->sectionGrid[$slot->section_id][$slot->day][$slot->period] = true;
            $this->teacherGrid[$slot->teacher_id][$slot->day][$slot->period] = true;
            if ($slot->room_id) {
                $this->roomGrid[$slot->room_id][$slot->day][$slot->period] = true;
            }
        }

        // Decompose allocations into prioritized tasks
        $combinedCohortTasks = [];
        $macroTasks = [];
        $doubleBlockTasks = [];
        $theoryTasks = [];
        $taskId = 0;

        $allocations = LessonAllocation::with(['subject', 'section', 'teacher', 'room'])->get();

        foreach ($allocations as $alloc) {
            $rem = $alloc->periods_per_week;
            $blockSize = $alloc->block_size ?: 1;
            $isDiff = $alloc->subject?->is_difficult ?? false;
            $syncId = $alloc->sync_group_id;
            $isCombined = $alloc->is_combined || (!empty($alloc->combined_group_id));

            if ($isCombined) {
                $groupId = $alloc->combined_group_id ?: "COMBINED_CLASS_{$alloc->section->grade_class_id}_SUB_{$alloc->subject_id}";
                for ($pIdx = 0; $pIdx < $rem; $pIdx++) {
                    $combinedCohortTasks[$groupId][$pIdx][] = [
                        'id' => $taskId++,
                        'allocation_id' => $alloc->id,
                        'section_id' => $alloc->section_id,
                        'teacher_id' => $alloc->teacher_id,
                        'subject_id' => $alloc->subject_id,
                        'room_id' => $alloc->room_id ?: $alloc->section->home_room_id,
                        'block_size' => 1,
                        'sync_group_id' => $syncId,
                        'is_combined' => true,
                        'combined_group_id' => $groupId,
                        'is_difficult' => $isDiff,
                        'sub_code' => $alloc->subject->code ?? $alloc->subject->name,
                        'sec_name' => $alloc->section->name,
                    ];
                }
                continue;
            }

            if ($blockSize > 1) {
                while ($rem >= $blockSize) {
                    $t = [
                        'id' => $taskId++,
                        'allocation_id' => $alloc->id,
                        'section_id' => $alloc->section_id,
                        'teacher_id' => $alloc->teacher_id,
                        'subject_id' => $alloc->subject_id,
                        'room_id' => $alloc->room_id ?: $alloc->section->home_room_id,
                        'block_size' => $blockSize,
                        'sync_group_id' => $syncId,
                        'is_combined' => false,
                        'combined_group_id' => null,
                        'is_difficult' => $isDiff,
                        'sub_code' => $alloc->subject->code,
                        'sec_name' => $alloc->section->name,
                    ];
                    if ($syncId) {
                        $macroTasks[$syncId][] = $t;
                    } else {
                        $doubleBlockTasks[] = $t;
                    }
                    $rem -= $blockSize;
                }
            }

            while ($rem > 0) {
                $theoryTasks[] = [
                    'id' => $taskId++,
                    'allocation_id' => $alloc->id,
                    'section_id' => $alloc->section_id,
                    'teacher_id' => $alloc->teacher_id,
                    'subject_id' => $alloc->subject_id,
                    'room_id' => $alloc->room_id ?: $alloc->section->home_room_id,
                    'block_size' => 1,
                    'sync_group_id' => null,
                    'is_combined' => false,
                    'combined_group_id' => null,
                    'is_difficult' => $isDiff,
                    'sub_code' => $alloc->subject->code,
                    'sec_name' => $alloc->section->name,
                ];
                $rem--;
            }
        }

        // Stage 0: Schedule Combined Cohort Sessions (e.g. MPR Mass Lectures for all sections of a grade)
        foreach ($combinedCohortTasks as $groupId => $periodsArray) {
            foreach ($periodsArray as $pIdx => $cohortCluster) {
                $firstTask = $cohortCluster[0];
                $teacherId = $firstTask['teacher_id'];
                $roomId = $firstTask['room_id'];
                $subId = $firstTask['subject_id'];
                $placed = false;

                foreach ($this->workingDays as $day) {
                    if ($placed) break;
                    $limit = $this->dayPeriodLimits[$day];

                    for ($p = 1; $p <= $limit; $p++) {
                        $uKey = "{$day}_{$p}";
                        if (!empty($this->teacherUnavailable[$teacherId][$uKey])) {
                            continue;
                        }

                        if (!empty($this->teacherGrid[$teacherId][$day][$p])) {
                            continue;
                        }

                        if ($roomId && !empty($this->roomGrid[$roomId][$day][$p])) {
                            continue;
                        }

                        $allSectionsFree = true;
                        foreach ($cohortCluster as $task) {
                            $secId = $task['section_id'];
                            if (!empty($this->sectionGrid[$secId][$day][$p])) {
                                $allSectionsFree = false;
                                break;
                            }
                            if (($this->sectionSubDayCount[$secId][$subId][$day] ?? 0) > 0) {
                                $allSectionsFree = false;
                                break;
                            }
                        }

                        if (!$allSectionsFree) {
                            continue;
                        }

                        // Place all sections in the cohort cluster at this exact day and period
                        $this->teacherGrid[$teacherId][$day][$p] = true;
                        if ($roomId) {
                            $this->roomGrid[$roomId][$day][$p] = true;
                        }

                        foreach ($cohortCluster as $task) {
                            $secId = $task['section_id'];
                            $this->sectionGrid[$secId][$day][$p] = true;
                            $this->sectionSubDayCount[$secId][$subId][$day] = ($this->sectionSubDayCount[$secId][$subId][$day] ?? 0) + 1;
                            $this->assignments[$task['id']] = [
                                'day' => $day,
                                'period' => $p,
                                'task' => $task,
                            ];
                        }
                        $this->teacherDayCount[$teacherId][$day] = ($this->teacherDayCount[$teacherId][$day] ?? 0) + 1;

                        $placed = true;
                        $this->iterations++;
                        break;
                    }
                }

                if (!$placed) {
                    return [
                        'success' => false,
                        'message' => "Unable to align combined cohort session: {$groupId} (Period #{$pIdx}).",
                        'duration_ms' => round((microtime(true) - $this->startTime) * 1000, 2),
                        'slots_assigned' => 0,
                    ];
                }
            }
        }

        // Stage 1: Schedule Synchronized Parallel Practicals (Macro-Tasks)
        foreach ($macroTasks as $syncId => $cluster) {
            $blockSize = $cluster[0]['block_size'];
            $placed = false;

            foreach ($this->workingDays as $day) {
                if ($placed) break;
                $limit = $this->dayPeriodLimits[$day];

                for ($p = 1; $p <= ($limit - $blockSize + 1); $p++) {
                    $endP = $p + $blockSize - 1;
                    if ($blockSize > 1 && $p <= $this->breakAfterPeriod && $endP > $this->breakAfterPeriod) {
                        continue;
                    }

                    $canFit = true;
                    foreach ($cluster as $task) {
                        if (!$this->isSlotValid($task, $day, $p)) {
                            $canFit = false;
                            break;
                        }
                    }

                    if ($canFit) {
                        foreach ($cluster as $task) {
                            $this->occupySlot($task, $day, $p);
                            $this->assignments[$task['id']] = ['day' => $day, 'period' => $p, 'task' => $task];
                        }
                        $placed = true;
                        $this->iterations++;
                        break;
                    }
                }
            }

            if (!$placed) {
                return [
                    'success' => false,
                    'message' => "Unable to align synchronized macro-group: {$syncId}.",
                    'duration_ms' => round((microtime(true) - $this->startTime) * 1000, 2),
                    'slots_assigned' => 0,
                ];
            }
        }

        // Stage 2: Schedule Other Specialized Double-Period Blocks
        foreach ($doubleBlockTasks as $task) {
            $blockSize = $task['block_size'];
            $placed = false;

            foreach ($this->workingDays as $day) {
                if ($placed) break;
                $limit = $this->dayPeriodLimits[$day];

                for ($p = 1; $p <= ($limit - $blockSize + 1); $p++) {
                    if ($this->isSlotValid($task, $day, $p)) {
                        $this->occupySlot($task, $day, $p);
                        $this->assignments[$task['id']] = ['day' => $day, 'period' => $p, 'task' => $task];
                        $placed = true;
                        $this->iterations++;
                        break;
                    }
                }
            }

            if (!$placed) {
                return [
                    'success' => false,
                    'message' => "Unable to schedule lab block for section: {$task['sec_name']}.",
                    'duration_ms' => round((microtime(true) - $this->startTime) * 1000, 2),
                    'slots_assigned' => 0,
                ];
            }
        }

        // Stage 3: Schedule Theory Lessons Section-by-Section using Minimum Remaining Values (MRV)
        $theoryBySec = [];
        foreach ($theoryTasks as $t) {
            $theoryBySec[$t['section_id']][] = $t;
        }

        foreach ($theoryBySec as $secId => $tasks) {
            $unassigned = [];
            foreach ($tasks as $t) {
                $unassigned[$t['id']] = $t;
            }

            if (!$this->solveTheorySectionMrv($secId, $unassigned)) {
                return [
                    'success' => false,
                    'message' => "Could not complete conflict-free assignment for section ID {$secId}.",
                    'duration_ms' => round((microtime(true) - $this->startTime) * 1000, 2),
                    'slots_assigned' => 0,
                ];
            }
        }

        $this->solveDurationMs = round((microtime(true) - $this->startTime) * 1000, 2);

        if ($persist) {
            $this->persistToDatabase();
        }

        $totalScheduledPeriods = 0;
        foreach ($this->assignments as $a) {
            $totalScheduledPeriods += $a['task']['block_size'];
        }

        return [
            'success' => true,
            'message' => "Timetable generated with 100% hard constraints satisfied ({$totalScheduledPeriods} slots).",
            'duration_ms' => $this->solveDurationMs,
            'iterations' => $this->iterations,
            'slots_assigned' => $totalScheduledPeriods,
            'total_tasks' => count($this->assignments),
            'sync_groups_aligned' => count($macroTasks),
        ];
    }

    protected function isSlotValid(array $task, string $day, int $startPeriod): bool
    {
        $blockSize = $task['block_size'];
        $endPeriod = $startPeriod + $blockSize - 1;
        $limit = $this->dayPeriodLimits[$day];

        // Friday / Daily period limits
        if ($endPeriod > $limit) {
            return false;
        }

        // Recess break non-interruption
        if ($blockSize > 1 && $startPeriod <= $this->breakAfterPeriod && $endPeriod > $this->breakAfterPeriod) {
            return false;
        }

        $secId = $task['section_id'];
        $teacherId = $task['teacher_id'];
        $roomId = $task['room_id'];

        for ($p = $startPeriod; $p <= $endPeriod; $p++) {
            if (!empty($this->sectionGrid[$secId][$day][$p])) return false;
            if (!empty($this->teacherGrid[$teacherId][$day][$p])) return false;
            if ($roomId && !empty($this->roomGrid[$roomId][$day][$p])) return false;

            $uKey = "{$day}_{$p}";
            if (!empty($this->teacherUnavailable[$teacherId][$uKey])) return false;
        }

        return true;
    }

    protected function occupySlot(array $task, string $day, int $startPeriod): void
    {
        $blockSize = $task['block_size'];
        $secId = $task['section_id'];
        $teacherId = $task['teacher_id'];
        $roomId = $task['room_id'];
        $subId = $task['subject_id'];

        for ($p = $startPeriod; $p < $startPeriod + $blockSize; $p++) {
            $this->sectionGrid[$secId][$day][$p] = true;
            $this->teacherGrid[$teacherId][$day][$p] = true;
            if ($roomId) {
                $this->roomGrid[$roomId][$day][$p] = true;
            }
        }

        $this->sectionSubDayCount[$secId][$subId][$day] =
            ($this->sectionSubDayCount[$secId][$subId][$day] ?? 0) + $blockSize;

        $this->teacherDayCount[$teacherId][$day] =
            ($this->teacherDayCount[$teacherId][$day] ?? 0) + $blockSize;
    }

    protected function unoccupySlot(array $task, string $day, int $startPeriod): void
    {
        $blockSize = $task['block_size'];
        $secId = $task['section_id'];
        $teacherId = $task['teacher_id'];
        $roomId = $task['room_id'];
        $subId = $task['subject_id'];

        for ($p = $startPeriod; $p < $startPeriod + $blockSize; $p++) {
            unset($this->sectionGrid[$secId][$day][$p]);
            unset($this->teacherGrid[$teacherId][$day][$p]);
            if ($roomId) {
                unset($this->roomGrid[$roomId][$day][$p]);
            }
        }

        $this->sectionSubDayCount[$secId][$subId][$day] -= $blockSize;
        if ($this->sectionSubDayCount[$secId][$subId][$day] <= 0) {
            unset($this->sectionSubDayCount[$secId][$subId][$day]);
        }

        $this->teacherDayCount[$teacherId][$day] -= $blockSize;
        if ($this->teacherDayCount[$teacherId][$day] <= 0) {
            unset($this->teacherDayCount[$teacherId][$day]);
        }
    }

    protected function solveTheorySectionMrv(int $secId, array &$unassigned): bool
    {
        $this->iterations++;
        if (empty($unassigned)) {
            return true;
        }

        // Pick task with Minimum Remaining Values (MRV)
        $bestId = null;
        $minCands = PHP_INT_MAX;
        $bestCandidates = [];

        foreach ($unassigned as $tId => $task) {
            $cands = [];
            foreach ($this->workingDays as $day) {
                $limit = $this->dayPeriodLimits[$day];
                for ($p = 1; $p <= $limit; $p++) {
                    if ($this->isSlotValid($task, $day, $p)) {
                        $pen = 0;
                        // Soft constraint: Subject dispersion across days
                        $dC = $this->sectionSubDayCount[$secId][$task['subject_id']][$day] ?? 0;
                        if ($dC > 0) $pen += ($dC * 40);
                        // Soft constraint: Cognitive fatigue for difficult subjects in afternoon
                        if ($task['is_difficult'] && $p >= 6) $pen += 25;
                        // Soft constraint: Teacher load balance
                        $tDay = $this->teacherDayCount[$task['teacher_id']][$day] ?? 0;
                        if ($tDay >= 5) $pen += 20;

                        $cands[] = ['day' => $day, 'period' => $p, 'pen' => $pen];
                    }
                }
            }

            $cCount = count($cands);
            if ($cCount === 0) {
                return false; // Dead end, backtrack early
            }

            if ($cCount < $minCands) {
                $minCands = $cCount;
                $bestId = $tId;
                $bestCandidates = $cands;
                if ($cCount === 1) break;
            }
        }

        $task = $unassigned[$bestId];
        unset($unassigned[$bestId]);

        // Sort candidates by soft constraint penalty
        usort($bestCandidates, fn($a, $b) => $a['pen'] <=> $b['pen']);

        foreach ($bestCandidates as $cand) {
            $day = $cand['day'];
            $p = $cand['period'];

            if (!$this->isSlotValid($task, $day, $p)) {
                continue;
            }

            $this->occupySlot($task, $day, $p);
            $this->assignments[$bestId] = ['day' => $day, 'period' => $p, 'task' => $task];

            if ($this->solveTheorySectionMrv($secId, $unassigned)) {
                return true;
            }

            $this->unoccupySlot($task, $day, $p);
            unset($this->assignments[$bestId]);
        }

        $unassigned[$bestId] = $task;
        return false;
    }

    protected function persistToDatabase(): void
    {
        DB::transaction(function () {
            TimetableSlot::where('is_locked', false)->delete();

            $slotsToInsert = [];
            $now = now();

            foreach ($this->assignments as $a) {
                $task = $a['task'];
                $day = $a['day'];
                $startPeriod = $a['period'];
                $blockSize = $task['block_size'];

                for ($p = $startPeriod; $p < $startPeriod + $blockSize; $p++) {
                    $slotsToInsert[] = [
                        'lesson_allocation_id' => $task['allocation_id'],
                        'section_id' => $task['section_id'],
                        'teacher_id' => $task['teacher_id'],
                        'subject_id' => $task['subject_id'],
                        'room_id' => $task['room_id'],
                        'day' => $day,
                        'period' => $p,
                        'is_locked' => false,
                        'is_combined' => !empty($task['is_combined']),
                        'combined_group_id' => $task['combined_group_id'] ?? null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            foreach (array_chunk($slotsToInsert, 100) as $chunk) {
                TimetableSlot::insert($chunk);
            }
        });
    }
}
