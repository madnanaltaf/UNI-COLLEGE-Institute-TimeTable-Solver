<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstitutionSetting;
use App\Models\Teacher;
use App\Models\GradeClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\LessonAllocation;
use App\Models\TimetableSlot;
use App\Models\DutyType;
use App\Models\DutySlot;
use App\Models\DutyAssignment;
use App\Services\Solver\FeasibilityChecker;
use App\Services\Solver\TimetableCspSolver;
use App\Services\Solver\DutyRosterOptimizer;
use App\Services\Export\ExportService;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class TimetableController extends Controller
{
    protected InstitutionSetting $setting;

    public function __construct()
    {
        $this->setting = InstitutionSetting::getActive();
    }

    public function dashboard()
    {
        $checker = new FeasibilityChecker();
        $feasibility = $checker->check();

        $totalSlots = TimetableSlot::count();
        $totalDuties = DutyAssignment::count();
        $totalAllocations = LessonAllocation::count();
        $totalSections = Section::count();
        $totalTeachers = Teacher::count();
        $totalRooms = Classroom::count();
        $totalSubjects = Subject::count();

        return view('dashboard', [
            'setting' => $this->setting,
            'feasibility' => $feasibility,
            'totalSlots' => $totalSlots,
            'totalDuties' => $totalDuties,
            'totalAllocations' => $totalAllocations,
            'totalSections' => $totalSections,
            'totalTeachers' => $totalTeachers,
            'totalRooms' => $totalRooms,
            'totalSubjects' => $totalSubjects,
        ]);
    }

    public function solve(Request $request)
    {
        ini_set('memory_limit', '512M');

        $solver = new TimetableCspSolver($this->setting);
        $result = $solver->solve(true);

        if ($result['success']) {
            $dutyOptimizer = new DutyRosterOptimizer($this->setting);
            $dutyResult = $dutyOptimizer->optimize();

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'duration_ms' => $result['duration_ms'],
                'slots_assigned' => $result['slots_assigned'],
                'sync_groups_aligned' => $result['sync_groups_aligned'] ?? 0,
                'duties_assigned' => $dutyResult['total_assigned'] ?? 0,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 422);
    }

    public function timetable(Request $request)
    {
        $mode = $request->query('mode', 'section'); // section, teacher, room, master
        $id = $request->query('id');

        $sections = Section::with(['gradeClass', 'classTeacher', 'homeRoom'])->orderBy('name')->get();
        $teachers = Teacher::with('primarySubject')->orderBy('name')->get();
        $rooms = Classroom::orderBy('name')->get();
        $subjects = Subject::all();

        $activeEntity = null;
        $slots = [];

        $workingDays = $this->setting->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $maxPeriods = $this->setting->getMaxPeriodsAnyDay();
        $periodTimes = $this->setting->generateDefaultPeriodTimes();

        if ($mode === 'section') {
            $activeSection = $id ? Section::with(['classTeacher', 'homeRoom', 'gradeClass'])->find($id) : $sections->first();
            $activeEntity = $activeSection;
            if ($activeSection) {
                $rawSlots = TimetableSlot::with(['subject', 'teacher', 'room'])
                    ->where('section_id', $activeSection->id)
                    ->get();
                foreach ($rawSlots as $s) {
                    $slots[$s->day][$s->period] = $s;
                }
            }
        } elseif ($mode === 'teacher') {
            $activeTeacher = $id ? Teacher::with(['primarySubject', 'inchargeSection'])->find($id) : $teachers->first();
            $activeEntity = $activeTeacher;
            if ($activeTeacher) {
                $rawSlots = TimetableSlot::with(['subject', 'section.gradeClass', 'room'])
                    ->where('teacher_id', $activeTeacher->id)
                    ->get();
                foreach ($rawSlots as $s) {
                    if (isset($slots[$s->day][$s->period])) {
                        $existing = $slots[$s->day][$s->period];
                        $secList = $existing->combined_sections ?? [$existing->section->name];
                        if (!in_array($s->section->name, $secList)) {
                            $secList[] = $s->section->name;
                        }
                        sort($secList);
                        $existing->combined_sections = $secList;
                        $existing->is_combined_view = true;
                        $existing->cohort_class_name = $s->section->gradeClass?->name ?? 'Grade Cohort';
                        $slots[$s->day][$s->period] = $existing;
                    } else {
                        $s->combined_sections = [$s->section->name];
                        $s->cohort_class_name = $s->section->gradeClass?->name ?? 'Grade Cohort';
                        $slots[$s->day][$s->period] = $s;
                    }
                }
            }
        } elseif ($mode === 'room') {
            $activeRoom = $id ? Classroom::find($id) : $rooms->first();
            $activeEntity = $activeRoom;
            if ($activeRoom) {
                $rawSlots = TimetableSlot::with(['subject', 'section.gradeClass', 'teacher'])
                    ->where('room_id', $activeRoom->id)
                    ->get();
                foreach ($rawSlots as $s) {
                    if (isset($slots[$s->day][$s->period])) {
                        $existing = $slots[$s->day][$s->period];
                        $secList = $existing->combined_sections ?? [$existing->section->name];
                        if (!in_array($s->section->name, $secList)) {
                            $secList[] = $s->section->name;
                        }
                        sort($secList);
                        $existing->combined_sections = $secList;
                        $existing->is_combined_view = true;
                        $existing->cohort_class_name = $s->section->gradeClass?->name ?? 'Grade Cohort';
                        $slots[$s->day][$s->period] = $existing;
                    } else {
                        $s->combined_sections = [$s->section->name];
                        $s->cohort_class_name = $s->section->gradeClass?->name ?? 'Grade Cohort';
                        $slots[$s->day][$s->period] = $s;
                    }
                }
            }
        } elseif ($mode === 'master') {
            $rawSlots = TimetableSlot::with(['subject', 'section', 'teacher', 'room'])->get();
            foreach ($rawSlots as $s) {
                $slots[$s->day][$s->section_id][$s->period] = $s;
            }
        }

        return view('timetable', [
            'setting' => $this->setting,
            'mode' => $mode,
            'activeEntity' => $activeEntity,
            'sections' => $sections,
            'teachers' => $teachers,
            'rooms' => $rooms,
            'subjects' => $subjects,
            'slots' => $slots,
            'workingDays' => $workingDays,
            'maxPeriods' => $maxPeriods,
            'periodTimes' => $periodTimes,
        ]);
    }

    public function updateSlot(Request $request)
    {
        $slotId = $request->input('slot_id') ?? $request->json('slot_id');
        $newDay = $request->input('day') ?? $request->json('day');
        $newPeriod = (int) ($request->input('period') ?? $request->json('period'));
        $newTeacherId = $request->input('teacher_id') ?? $request->json('teacher_id');
        $newRoomId = $request->input('room_id') ?? $request->json('room_id');

        $slot = TimetableSlot::with(['section.gradeClass', 'teacher', 'room', 'subject'])->find($slotId);
        if (!$slot) {
            return response()->json(['success' => false, 'message' => 'Slot record not found.'], 404);
        }

        // Validate Daily Period Limit (e.g. Friday early dismissal)
        $dayLimit = $this->setting->getPeriodsForDay($newDay);
        if ($newPeriod > $dayLimit) {
            return response()->json([
                'success' => false,
                'message' => "Cannot place slot on {$newDay} Period {$newPeriod}: Active period limit for {$newDay} is {$dayLimit} periods.",
            ], 422);
        }

        $teacherId = $newTeacherId ?: $slot->teacher_id;
        $roomId = $newRoomId ?: $slot->room_id;

        // Check if this slot belongs to a combined cohort session (e.g. MPR lecture for all sections of a class)
        $isCombined = $slot->is_combined || !empty($slot->combined_group_id);
        $siblingSlots = collect([$slot]);

        if ($isCombined && !empty($slot->combined_group_id)) {
            $siblingSlots = TimetableSlot::where('combined_group_id', $slot->combined_group_id)
                ->where('day', $slot->day)
                ->where('period', $slot->period)
                ->with(['section', 'teacher', 'room', 'subject'])
                ->get();
        } elseif ($slot->section && $slot->section->grade_class_id) {
            // Check if other sections of the same grade class share the same teacher, subject, room, day, and period
            $classSecIds = Section::where('grade_class_id', $slot->section->grade_class_id)->pluck('id');
            $foundSiblings = TimetableSlot::whereIn('section_id', $classSecIds)
                ->where('teacher_id', $slot->teacher_id)
                ->where('subject_id', $slot->subject_id)
                ->where('day', $slot->day)
                ->where('period', $slot->period)
                ->with(['section', 'teacher', 'room', 'subject'])
                ->get();
            if ($foundSiblings->count() > 1) {
                $siblingSlots = $foundSiblings;
                $isCombined = true;
            }
        }

        $siblingIds = $siblingSlots->pluck('id')->toArray();

        // 1. Check Teacher availability & double-booking (excluding this combined cohort)
        $teacher = Teacher::find($teacherId);
        if (!$teacher->isAvailable($newDay, $newPeriod)) {
            return response()->json([
                'success' => false,
                'message' => "Teacher {$teacher->name} is unavailable on {$newDay} Period {$newPeriod} (Blocked in Faculty Matrix).",
            ], 422);
        }

        $teacherConflict = TimetableSlot::where('teacher_id', $teacherId)
            ->where('day', $newDay)
            ->where('period', $newPeriod)
            ->whereNotIn('id', $siblingIds)
            ->with('section')
            ->first();

        if ($teacherConflict) {
            return response()->json([
                'success' => false,
                'message' => "Teacher Conflict: {$teacher->name} is already teaching Section {$teacherConflict->section->name} on {$newDay} Period {$newPeriod}.",
            ], 422);
        }

        // 2. Check Section double-booking (for ALL sections in the cohort cluster)
        foreach ($siblingSlots as $sib) {
            $secConflict = TimetableSlot::where('section_id', $sib->section_id)
                ->where('day', $newDay)
                ->where('period', $newPeriod)
                ->whereNotIn('id', $siblingIds)
                ->with('subject', 'section')
                ->first();

            if ($secConflict) {
                return response()->json([
                    'success' => false,
                    'message' => "Section Collision: Section {$sib->section->name} already has {$secConflict->subject->code} on {$newDay} Period {$newPeriod}.",
                ], 422);
            }
        }

        // 3. Check Room double-booking (excluding this combined cohort)
        if ($roomId) {
            $roomConflict = TimetableSlot::where('room_id', $roomId)
                ->where('day', $newDay)
                ->where('period', $newPeriod)
                ->whereNotIn('id', $siblingIds)
                ->with('section')
                ->first();

            if ($roomConflict) {
                return response()->json([
                    'success' => false,
                    'message' => "Room Collision: Room already booked by Section {$roomConflict->section->name} on {$newDay} Period {$newPeriod}.",
                ], 422);
            }
        }

        // Apply Update across ALL sibling slots in the cohort
        $secNames = [];
        $targetRoom = Classroom::find($roomId);
        foreach ($siblingSlots as $sib) {
            $sib->day = $newDay;
            $sib->period = $newPeriod;
            $sib->teacher_id = $teacherId;
            $sib->room_id = $roomId;
            if ($isCombined) {
                $sib->is_combined = true;
                if (empty($sib->combined_group_id)) {
                    $sib->combined_group_id = "COMBINED_CLASS_{$sib->section->grade_class_id}_SUB_{$sib->subject_id}";
                }
            }
            $sib->save();
            $secNames[] = $sib->section->name;
        }

        if (count($secNames) > 1) {
            return response()->json([
                'success' => true,
                'message' => "Combined cohort session updated: All sections (" . implode(', ', $secNames) . ") moved together to {$newDay} Period {$newPeriod} with {$teacher->name} in " . ($targetRoom->name ?? 'Room') . ".",
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Timetable slot successfully updated with zero conflicts.",
        ]);
    }

    public function allotments(Request $request)
    {
        $viewMode = $request->query('view_mode', 'section'); // 'section' or 'teacher'
        $id = $request->query('id');

        $sections = Section::with(['gradeClass', 'homeRoom', 'classTeacher', 'lessonAllocations.subject', 'lessonAllocations.teacher', 'lessonAllocations.room'])->orderBy('name')->get();
        $teachers = Teacher::with(['primarySubject', 'lessonAllocations.section', 'lessonAllocations.subject'])->orderBy('name')->get();
        $subjects = Subject::with('teachers')->orderBy('name')->get();
        $rooms = Classroom::orderBy('name')->get();

        $activeSection = null;
        $activeTeacher = null;
        $allocations = [];

        $totalWeeklySlots = $this->setting->getTotalWeeklySlots();

        if ($viewMode === 'section') {
            $activeSection = $id ? Section::with(['lessonAllocations.subject', 'lessonAllocations.teacher', 'lessonAllocations.room'])->find($id) : $sections->first();
            $allocations = $activeSection ? $activeSection->lessonAllocations : collect([]);
        } else {
            $activeTeacher = $id ? Teacher::with(['primarySubject', 'lessonAllocations.section', 'lessonAllocations.subject', 'lessonAllocations.room'])->find($id) : $teachers->first();
            $allocations = $activeTeacher ? $activeTeacher->lessonAllocations : collect([]);
        }

        return view('allotments', [
            'setting' => $this->setting,
            'viewMode' => $viewMode,
            'sections' => $sections,
            'teachers' => $teachers,
            'subjects' => $subjects,
            'rooms' => $rooms,
            'activeSection' => $activeSection,
            'activeTeacher' => $activeTeacher,
            'allocations' => $allocations,
            'totalWeeklySlots' => $totalWeeklySlots,
        ]);
    }

    public function storeAllotment(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_id' => 'nullable',
            'periods_per_week' => 'required|integer|min:1|max:32',
            'block_size' => 'required|integer|min:1|max:4',
            'sync_group_id' => 'nullable|string|max:50',
            'is_combined' => 'nullable|boolean',
        ]);

        $validated['room_id'] = !empty($validated['room_id']) ? (int)$validated['room_id'] : null;
        $validated['sync_group_id'] = !empty($validated['sync_group_id']) ? trim($validated['sync_group_id']) : null;
        $isCombined = $request->boolean('is_combined');

        if ($isCombined) {
            $section = Section::findOrFail($validated['section_id']);
            $gradeClassId = $section->grade_class_id;
            $classSections = Section::where('grade_class_id', $gradeClassId)->get();
            $combinedGroupId = "COMBINED_CLASS_{$gradeClassId}_SUB_{$validated['subject_id']}";

            foreach ($classSections as $sec) {
                LessonAllocation::updateOrCreate(
                    [
                        'section_id' => $sec->id,
                        'subject_id' => $validated['subject_id'],
                    ],
                    [
                        'teacher_id' => $validated['teacher_id'],
                        'room_id' => $validated['room_id'],
                        'periods_per_week' => $validated['periods_per_week'],
                        'block_size' => $validated['block_size'],
                        'is_combined' => true,
                        'combined_group_id' => $combinedGroupId,
                        'sync_group_id' => $combinedGroupId,
                    ]
                );
            }

            return redirect()->back()->with('success', "Combined cohort lesson allocation added for all sections of {$section->gradeClass->name} (" . $classSections->pluck('name')->implode(', ') . ").");
        }

        $validated['is_combined'] = false;
        $validated['combined_group_id'] = null;
        LessonAllocation::create($validated);

        return redirect()->back()->with('success', 'Curriculum lesson allocation added successfully.');
    }

    public function updateAllotment(Request $request, $id)
    {
        $allocation = LessonAllocation::findOrFail($id);

        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_id' => 'nullable',
            'periods_per_week' => 'required|integer|min:1|max:32',
            'block_size' => 'required|integer|min:1|max:4',
            'sync_group_id' => 'nullable|string|max:50',
            'is_combined' => 'nullable|boolean',
        ]);

        $validated['room_id'] = !empty($validated['room_id']) ? (int)$validated['room_id'] : null;
        $validated['sync_group_id'] = !empty($validated['sync_group_id']) ? trim($validated['sync_group_id']) : null;
        $isCombined = $request->boolean('is_combined') || $allocation->is_combined;

        $oldTeacherId = $allocation->teacher_id;
        $oldSectionId = $allocation->section_id;
        $oldSubjectId = $allocation->subject_id;

        if ($isCombined) {
            $section = Section::findOrFail($validated['section_id']);
            $combinedGroupId = $allocation->combined_group_id ?: "COMBINED_CLASS_{$section->grade_class_id}_SUB_{$validated['subject_id']}";
            $classSections = Section::where('grade_class_id', $section->grade_class_id)->get();

            foreach ($classSections as $sec) {
                LessonAllocation::updateOrCreate(
                    [
                        'section_id' => $sec->id,
                        'subject_id' => $validated['subject_id'],
                    ],
                    [
                        'teacher_id' => $validated['teacher_id'],
                        'room_id' => $validated['room_id'],
                        'periods_per_week' => $validated['periods_per_week'],
                        'block_size' => $validated['block_size'],
                        'is_combined' => true,
                        'combined_group_id' => $combinedGroupId,
                        'sync_group_id' => $combinedGroupId,
                    ]
                );
            }

            // Also keep timetable slots synchronized for all sibling sections
            $slotRoomId = $validated['room_id'];
            if (!$slotRoomId) {
                $slotRoomId = $section->home_room_id;
            }

            TimetableSlot::where('combined_group_id', $combinedGroupId)
                ->orWhere(function ($q) use ($classSections, $oldSubjectId) {
                    $q->whereIn('section_id', $classSections->pluck('id'))
                      ->where('subject_id', $oldSubjectId);
                })
                ->update([
                    'teacher_id' => $validated['teacher_id'],
                    'subject_id' => $validated['subject_id'],
                    'room_id' => $slotRoomId,
                    'is_combined' => true,
                    'combined_group_id' => $combinedGroupId,
                ]);

            return redirect()->back()->with('success', "Combined cohort allocation synchronized across all sections of {$section->gradeClass->name}.");
        }

        $allocation->update($validated);

        // Keep timetable slots synchronized
        $slotRoomId = $validated['room_id'];
        if (!$slotRoomId) {
            $section = Section::find($validated['section_id']);
            $slotRoomId = $section?->home_room_id;
        }

        TimetableSlot::where('teacher_id', $oldTeacherId)
            ->where('section_id', $oldSectionId)
            ->where('subject_id', $oldSubjectId)
            ->update([
                'teacher_id' => $validated['teacher_id'],
                'section_id' => $validated['section_id'],
                'subject_id' => $validated['subject_id'],
                'room_id' => $slotRoomId,
            ]);

        return redirect()->back()->with('success', 'Lesson allocation and linked timetable slots updated successfully.');
    }

    public function deleteAllotment($id)
    {
        LessonAllocation::destroy($id);
        return redirect()->back()->with('success', 'Lesson allocation removed.');
    }

    public function faculty(Request $request)
    {
        $teachers = Teacher::with(['primarySubject', 'inchargeSection', 'lessonAllocations'])->orderBy('name')->get();
        $subjects = Subject::all();
        $workingDays = $this->setting->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $maxPeriods = $this->setting->getMaxPeriodsAnyDay();

        $selectedTeacherId = $request->query('teacher_id', $teachers->first()?->id);
        $selectedTeacher = Teacher::with(['primarySubject', 'inchargeSection'])->find($selectedTeacherId);

        return view('faculty', [
            'setting' => $this->setting,
            'teachers' => $teachers,
            'subjects' => $subjects,
            'selectedTeacher' => $selectedTeacher,
            'workingDays' => $workingDays,
            'maxPeriods' => $maxPeriods,
        ]);
    }

    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10|unique:teachers,code',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'primary_subject_id' => 'required|exists:subjects,id',
            'max_periods_per_week' => 'required|integer|min:1|max:40',
            'max_periods_per_day' => 'required|integer|min:1|max:10',
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = Teacher::generateInitials($validated['name']);
        }
        $validated['qualified_subjects'] = [(int)$validated['primary_subject_id']];
        $validated['unavailable_slots'] = [];

        $teacher = Teacher::create($validated);

        return redirect()->route('faculty', ['teacher_id' => $teacher->id])
            ->with('success', "Faculty member {$teacher->name} added successfully.");
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:10', Rule::unique('teachers', 'code')->ignore($teacher->id)],
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'primary_subject_id' => 'required|exists:subjects,id',
            'max_periods_per_week' => 'required|integer|min:1|max:40',
            'max_periods_per_day' => 'required|integer|min:1|max:10',
            'qualified_subjects' => 'nullable|array',
            'qualified_subjects.*' => 'exists:subjects,id',
        ]);

        $oldPrimaryId = $teacher->primary_subject_id;
        $primaryId = (int)$validated['primary_subject_id'];
        // Strict 1-Teacher = 1-Specialist model: teacher has one single subject
        $validated['qualified_subjects'] = [$primaryId];

        $teacher->update($validated);

        // Keep all allocations and timetable slots in sync with the teacher's single subject
        $teacher->lessonAllocations()->update(['subject_id' => $primaryId]);
        $teacher->timetableSlots()->update(['subject_id' => $primaryId]);

        return redirect()->route('faculty', ['teacher_id' => $teacher->id])
            ->with('success', "Faculty member {$teacher->name} updated successfully. Single specialist subject set to {$teacher->primarySubject?->name}.");
    }

    public function deleteTeacher(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $name = $teacher->name;

        // Clean up relations
        $teacher->timetableSlots()->delete();
        $teacher->lessonAllocations()->delete();
        $teacher->dutyAssignments()->delete();
        Section::where('class_teacher_id', $teacher->id)->update(['class_teacher_id' => null]);

        $teacher->delete();

        return redirect()->route('faculty')
            ->with('success', "Faculty member {$name} deleted successfully.");
    }

    public function toggleAvailability(Request $request)
    {
        $teacherId = $request->input('teacher_id');
        $day = $request->input('day');
        $period = (int)$request->input('period');

        $teacher = Teacher::findOrFail($teacherId);

        if ($teacher->isAvailable($day, $period)) {
            $teacher->addUnavailableSlot($day, $period);
            $status = 'blocked';
        } else {
            $teacher->removeUnavailableSlot($day, $period);
            $status = 'free';
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => "Slot {$day} P{$period} is now {$status}.",
        ]);
    }

    public function applyAvailabilityPreset(Request $request)
    {
        $teacherId = $request->input('teacher_id');
        $preset = $request->input('preset'); // all_free, friday_dismissal, block_p1, block_late

        $teacher = Teacher::findOrFail($teacherId);
        $workingDays = $this->setting->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        if ($preset === 'all_free') {
            $teacher->unavailable_slots = [];
            $teacher->save();
        } elseif ($preset === 'friday_dismissal') {
            // Block periods 5..7 on Friday
            for ($p = 5; $p <= 8; $p++) {
                $teacher->addUnavailableSlot('Friday', $p);
            }
        } elseif ($preset === 'block_p1') {
            foreach ($workingDays as $d) {
                $teacher->addUnavailableSlot($d, 1);
            }
        } elseif ($preset === 'block_late') {
            foreach ($workingDays as $d) {
                $limit = $this->setting->getPeriodsForDay($d);
                if ($limit >= 6) {
                    $teacher->addUnavailableSlot($d, 6);
                    $teacher->addUnavailableSlot($d, 7);
                }
            }
        }

        return redirect()->route('faculty', ['teacher_id' => $teacher->id])
            ->with('success', "Preset '{$preset}' applied successfully to {$teacher->name}.");
    }

    public function classes()
    {
        $classes = GradeClass::with(['sections.classTeacher', 'sections.homeRoom'])->orderBy('order_num')->get();
        $teachers = Teacher::orderBy('name')->get();
        $rooms = Classroom::orderBy('name')->get();

        return view('classes', [
            'setting' => $this->setting,
            'classes' => $classes,
            'teachers' => $teachers,
            'rooms' => $rooms,
        ]);
    }

    public function updateSectionIncharge(Request $request)
    {
        $sectionId = $request->input('section_id');
        $teacherId = $request->input('teacher_id');

        $section = Section::findOrFail($sectionId);
        $section->class_teacher_id = $teacherId ?: null;
        $section->save();

        return redirect()->back()->with('success', "Designated Class Incharge updated for Section {$section->name}.");
    }

    public function subjects()
    {
        $subjects = Subject::withCount(['lessonAllocations', 'teachers'])->orderBy('name')->get();

        return view('subjects', [
            'setting' => $this->setting,
            'subjects' => $subjects,
        ]);
    }

    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:15|unique:subjects,code',
            'subject_type' => 'required|string',
            'requires_lab' => 'boolean',
            'default_room_type' => 'required|string',
            'color' => 'required|string|max:10',
            'is_difficult' => 'boolean',
            'allowed_double_period' => 'boolean',
        ]);

        Subject::create($validated);
        return redirect()->back()->with('success', 'Subject created successfully.');
    }

    public function updateSubject(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:15', Rule::unique('subjects', 'code')->ignore($subject->id)],
            'subject_type' => 'required|string',
            'default_room_type' => 'required|string',
            'color' => 'required|string|max:10',
        ]);

        $validated['requires_lab'] = $request->has('requires_lab');
        $validated['is_difficult'] = $request->has('is_difficult');
        $validated['allowed_double_period'] = $request->has('allowed_double_period');

        $subject->update($validated);

        return redirect()->route('subjects')->with('success', "Subject {$subject->name} updated successfully.");
    }

    public function deleteSubject(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $name = $subject->name;

        $subject->timetableSlots()->delete();
        $subject->lessonAllocations()->delete();
        $subject->delete();

        return redirect()->route('subjects')->with('success', "Subject {$name} deleted successfully.");
    }

    public function rooms()
    {
        $rooms = Classroom::withCount(['timetableSlots', 'homeSections'])->orderBy('name')->get();
        return view('rooms', [
            'setting' => $this->setting,
            'rooms' => $rooms,
        ]);
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_type' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'building' => 'required|string',
        ]);

        Classroom::create($validated);
        return redirect()->back()->with('success', 'Classroom or Laboratory created.');
    }

    public function updateRoom(Request $request, $id)
    {
        $room = Classroom::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_type' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'building' => 'required|string',
        ]);

        $room->update($validated);

        return redirect()->route('rooms')->with('success', "Room {$room->name} updated successfully.");
    }

    public function deleteRoom(Request $request, $id)
    {
        $room = Classroom::findOrFail($id);
        $name = $room->name;

        Section::where('home_room_id', $room->id)->update(['home_room_id' => null]);
        $room->timetableSlots()->update(['room_id' => null]);
        $room->lessonAllocations()->update(['room_id' => null]);
        $room->delete();

        return redirect()->route('rooms')->with('success', "Room {$name} deleted successfully.");
    }

    public function institution()
    {
        return view('institution', [
            'setting' => $this->setting,
        ]);
    }

    public function updateInstitution(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:50',
            'term' => 'required|string|max:50',
            'motto' => 'nullable|string|max:255',
            'working_days' => 'required|array|min:1',
            'periods_per_day' => 'required|integer|min:4|max:10',
            'friday_periods' => 'required|integer|min:3|max:8',
            'saturday_periods' => 'nullable|integer|min:1|max:10',
            'break_after_period' => 'required|integer|min:2|max:6',
            'break_duration_mins' => 'required|integer|min:10|max:60',
            'period_duration_mins' => 'required|integer|min:20|max:90',
            'start_hour' => 'required|integer|min:6|max:12',
            'start_min' => 'required|integer|min:0|max:59',
        ]);

        $workingDays = $validated['working_days'];
        $dayPeriods = [];
        foreach ($workingDays as $day) {
            if ($day === 'Friday') {
                $dayPeriods['Friday'] = (int) $validated['friday_periods'];
            } elseif ($day === 'Saturday' && !empty($validated['saturday_periods'])) {
                $dayPeriods['Saturday'] = (int) $validated['saturday_periods'];
            } else {
                $dayPeriods[$day] = (int) $validated['periods_per_day'];
            }
        }

        $validated['day_periods'] = $dayPeriods;
        $this->setting->update($validated);

        return redirect()->back()->with('success', 'Institution settings and working days calendar updated successfully.');
    }

    public function duties()
    {
        $dutySlots = DutySlot::with(['dutyType', 'assignments.teacher.primarySubject'])->orderBy('day')->orderBy('period')->get();
        $dutyTypes = DutyType::all();
        $teachers = Teacher::with('primarySubject')->orderBy('name')->get();

        $allAssignments = DutyAssignment::all();
        $teacherCounts = [];
        foreach ($allAssignments as $a) {
            $teacherCounts[$a->teacher_id] = ($teacherCounts[$a->teacher_id] ?? 0) + 1;
        }

        $audit = [];
        foreach ($teachers as $t) {
            $cnt = $teacherCounts[$t->id] ?? 0;
            $audit[] = [
                'teacher' => $t,
                'count' => $cnt,
            ];
        }

        $counts = array_column($audit, 'count');
        $minLoad = !empty($counts) ? min($counts) : 0;
        $maxLoad = !empty($counts) ? max($counts) : 0;
        $spread = $maxLoad - $minLoad;

        return view('duties', [
            'setting' => $this->setting,
            'dutySlots' => $dutySlots,
            'dutyTypes' => $dutyTypes,
            'audit' => $audit,
            'minLoad' => $minLoad,
            'maxLoad' => $maxLoad,
            'spread' => $spread,
            'totalDuties' => count($allAssignments),
        ]);
    }

    public function optimizeDuties()
    {
        $optimizer = new DutyRosterOptimizer($this->setting);
        $result = $optimizer->optimize();

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }
        return redirect()->back()->with('error', $result['message']);
    }

    public function exports()
    {
        return view('exports', [
            'setting' => $this->setting,
            'hasTimetable' => TimetableSlot::exists(),
        ]);
    }

    public function downloadPdf($type)
    {
        ini_set('memory_limit', '512M');
        $exporter = new ExportService($this->setting);

        if ($type === 'sections') {
            $pdfContent = $exporter->generateSectionsBookletPdf();
            return Response::make($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Class_Sections_Timetable_Booklet.pdf"',
            ]);
        } elseif ($type === 'faculty') {
            $pdfContent = $exporter->generateFacultyBookletPdf();
            return Response::make($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Faculty_Teachers_Timetable_Booklet.pdf"',
            ]);
        } elseif ($type === 'master') {
            $pdfContent = $exporter->generateMasterMatrixPdf();
            return Response::make($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Master_Institutional_Matrix.pdf"',
            ]);
        } elseif ($type === 'duty') {
            $pdfContent = $exporter->generateDutyRosterPdf();
            return Response::make($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Staff_Duty_Roster.pdf"',
            ]);
        } elseif ($type === 'subjects') {
            $pdfContent = $exporter->generateSubjectSchedulesPdf();
            return Response::make($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Subject_Schedules_Report.pdf"',
            ]);
        }

        abort(404);
    }

    public function downloadCsv($type)
    {
        $exporter = new ExportService($this->setting);
        if ($type === 'master') {
            $csv = $exporter->generateMasterTimetableCsv();
            return Response::make($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="Master_Timetable.csv"',
            ]);
        } elseif ($type === 'duty') {
            $csv = $exporter->generateDutyRosterCsv();
            return Response::make($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="Staff_Duty_Roster.csv"',
            ]);
        }
        abort(404);
    }

    public function downloadProjectJson()
    {
        $exporter = new ExportService($this->setting);
        $json = $exporter->exportProjectJson();
        return Response::make($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="timetable_project.json"',
        ]);
    }
}
