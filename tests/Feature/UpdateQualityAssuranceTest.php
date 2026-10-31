<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\InstitutionSetting;
use App\Models\LessonAllocation;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TimetableSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateQualityAssuranceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'InstitutionalDatabaseSeeder']);
    }

    public function test_institution_update_with_saturday_and_friday_caps(): void
    {
        $response = $this->from('/institution')->post('/institution', [
            'name' => 'Apex Grammar Higher Secondary School',
            'academic_year' => '2026-2027',
            'term' => 'Fall Term',
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'periods_per_day' => 7,
            'friday_periods' => 4,
            'saturday_periods' => 6,
            'break_after_period' => 4,
            'break_duration_mins' => 30,
            'period_duration_mins' => 45,
            'start_hour' => 8,
            'start_min' => 0,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/institution');

        $setting = InstitutionSetting::getActive();
        $this->assertEquals('Apex Grammar Higher Secondary School', $setting->name);
        $this->assertEquals(6, count($setting->working_days));
        $this->assertEquals(4, $setting->friday_periods);
        $this->assertEquals(6, $setting->saturday_periods);
        $this->assertEquals(4, $setting->getPeriodsForDay('Friday'));
        $this->assertEquals(6, $setting->getPeriodsForDay('Saturday'));
        $this->assertEquals(7, $setting->getPeriodsForDay('Monday'));
    }

    public function test_slot_update_with_conflict_validation(): void
    {
        // First solve timetable to generate slots
        $solver = new \App\Services\Solver\TimetableCspSolver(InstitutionSetting::getActive());
        $result = $solver->solve();
        $this->assertTrue($result['success']);

        $slot = TimetableSlot::first();
        $this->assertNotNull($slot);

        // Find a free period for this section and teacher
        $workingDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $freeDay = null;
        $freePeriod = null;

        foreach ($workingDays as $d) {
            $maxP = $d === 'Friday' ? 4 : ($d === 'Saturday' ? 6 : 7);
            for ($p = 1; $p <= $maxP; $p++) {
                $secBusy = TimetableSlot::where('section_id', $slot->section_id)->where('day', $d)->where('period', $p)->exists();
                $tchBusy = TimetableSlot::where('teacher_id', $slot->teacher_id)->where('day', $d)->where('period', $p)->exists();
                if (!$secBusy && !$tchBusy) {
                    $freeDay = $d;
                    $freePeriod = $p;
                    break 2;
                }
            }
        }

        if ($freeDay && $freePeriod) {
            $response = $this->postJson('/slot/update', [
                'slot_id' => $slot->id,
                'day' => $freeDay,
                'period' => $freePeriod,
                'teacher_id' => $slot->teacher_id,
                'room_id' => $slot->room_id,
            ]);

            $response->assertStatus(200);
            $response->assertJson(['success' => true]);

            $slot->refresh();
            $this->assertEquals($freeDay, $slot->day);
            $this->assertEquals($freePeriod, $slot->period);
        } else {
            // If completely packed, test conflict rejection returns 422
            $occupiedSlot = TimetableSlot::where('id', '!=', $slot->id)->first();
            $response = $this->postJson('/slot/update', [
                'slot_id' => $slot->id,
                'day' => $occupiedSlot->day,
                'period' => $occupiedSlot->period,
                'teacher_id' => $slot->teacher_id,
                'room_id' => $slot->room_id,
            ]);
            $response->assertStatus(422);
        }
    }

    public function test_assign_class_incharge_update(): void
    {
        $section = Section::first();
        $teacher = Teacher::first();

        $response = $this->post('/classes/incharge', [
            'section_id' => $section->id,
            'teacher_id' => $teacher->id,
        ]);

        $response->assertStatus(302);
        $section->refresh();
        $this->assertEquals($teacher->id, $section->class_teacher_id);
    }

    public function test_faculty_toggle_availability_update(): void
    {
        $teacher = Teacher::first();
        $wasAvailable = $teacher->isAvailable('Monday', 1);

        $response = $this->postJson('/faculty/toggle-availability', [
            'teacher_id' => $teacher->id,
            'day' => 'Monday',
            'period' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $teacher->refresh();
        $this->assertNotEquals($wasAvailable, $teacher->isAvailable('Monday', 1));
    }

    public function test_create_and_delete_lesson_allocation(): void
    {
        $section = Section::first();
        $subject = Subject::first();
        $teacher = Teacher::first();

        $response = $this->post('/allotments', [
            'section_id' => $section->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'periods_per_week' => 3,
            'block_size' => 1,
        ]);

        $response->assertStatus(302);

        $allocation = LessonAllocation::where('section_id', $section->id)
            ->where('subject_id', $subject->id)
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->first();

        $this->assertNotNull($allocation);

        // Delete allocation
        $delResponse = $this->delete('/allotments/' . $allocation->id);
        $delResponse->assertStatus(302);
        $this->assertNull(LessonAllocation::find($allocation->id));
    }

    public function test_update_lesson_allocation(): void
    {
        $allocation = LessonAllocation::first();
        $this->assertNotNull($allocation);
        $otherSubject = Subject::where('id', '!=', $allocation->subject_id)->first();

        $updateResponse = $this->put("/allotments/{$allocation->id}/update", [
            'section_id' => $allocation->section_id,
            'subject_id' => $otherSubject->id,
            'teacher_id' => $allocation->teacher_id,
            'periods_per_week' => 5,
            'block_size' => 1,
        ]);

        $updateResponse->assertStatus(302);
        $allocation->refresh();
        $this->assertEquals($otherSubject->id, $allocation->subject_id);
        $this->assertEquals(5, $allocation->periods_per_week);
    }

    public function test_add_faculty_member(): void
    {
        $subject = Subject::first();

        $response = $this->post('/faculty', [
            'name' => 'Prof. QA Specialist',
            'code' => 'PQS',
            'email' => 'pqs@school.edu',
            'phone' => '0300-1234567',
            'primary_subject_id' => $subject->id,
            'max_periods_per_week' => 24,
            'max_periods_per_day' => 5,
        ]);

        $response->assertStatus(302);

        $teacher = Teacher::where('code', 'PQS')->first();
        $this->assertNotNull($teacher);
        $this->assertEquals('Prof. QA Specialist', $teacher->name);
    }

    public function test_add_subject_and_room(): void
    {
        $subResponse = $this->post('/subjects', [
            'name' => 'Robotics & AI',
            'code' => 'ROBO',
            'subject_type' => 'Practical Lab',
            'color' => '#10b981',
            'is_difficult' => 1,
            'requires_lab' => 1,
            'default_room_type' => 'Computer Lab',
        ]);
        $subResponse->assertStatus(302);
        $this->assertNotNull(Subject::where('code', 'ROBO')->first());

        $roomResponse = $this->post('/rooms', [
            'name' => 'AI Innovation Lab',
            'room_type' => 'Computer Lab',
            'capacity' => 35,
            'building' => 'STEM Wing',
        ]);
        $roomResponse->assertStatus(302);
        $this->assertNotNull(Classroom::where('name', 'AI Innovation Lab')->first());
    }

    public function test_csp_solver_execution_via_ajax(): void
    {
        $response = $this->postJson('/solve');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $slotsCount = TimetableSlot::count();
        $this->assertGreaterThan(0, $slotsCount);
    }

    public function test_teacher_update_and_delete(): void
    {
        $teacher = Teacher::first();
        $this->assertNotNull($teacher);
        $subject = Subject::first();

        // Test PUT to /faculty/{id}/update as submitted by HTML form
        $updateResponse = $this->put("/faculty/{$teacher->id}/update", [
            'name' => 'Dr. Alexander Fleming Updated',
            'code' => 'AFU',
            'email' => 'afleming@apex.edu',
            'phone' => '+92-300-9998877',
            'primary_subject_id' => $subject->id,
            'max_periods_per_week' => 22,
            'max_periods_per_day' => 4,
            'qualified_subjects' => [$subject->id],
        ]);

        $updateResponse->assertStatus(302);
        $teacher->refresh();
        $this->assertEquals('Dr. Alexander Fleming Updated', $teacher->name);
        $this->assertEquals('AFU', $teacher->code);
        $this->assertEquals(22, $teacher->max_periods_per_week);
        $this->assertEquals(4, $teacher->max_periods_per_day);
        $this->assertEquals('afleming@apex.edu', $teacher->email);

        // Delete teacher
        $delResponse = $this->delete("/faculty/{$teacher->id}");
        $delResponse->assertStatus(302);
        $this->assertNull(Teacher::find($teacher->id));
    }

    public function test_subject_update_and_delete(): void
    {
        $subject = Subject::create([
            'name' => 'Temporary Subject',
            'code' => 'TEMP123',
            'subject_type' => 'Theory',
            'color' => '#ef4444',
            'default_room_type' => 'General Classroom',
            'requires_lab' => false,
            'is_difficult' => false,
            'allowed_double_period' => false,
        ]);

        $updateResponse = $this->put("/subjects/{$subject->id}", [
            'name' => 'Temporary Subject Updated',
            'code' => 'TEMP999',
            'subject_type' => 'Elective',
            'color' => '#10b981',
            'default_room_type' => 'Special Lab',
            'requires_lab' => '1',
            'is_difficult' => '1',
            'allowed_double_period' => '1',
        ]);

        $updateResponse->assertStatus(302);
        $subject->refresh();
        $this->assertEquals('Temporary Subject Updated', $subject->name);
        $this->assertEquals('TEMP999', $subject->code);
        $this->assertTrue($subject->requires_lab);

        $delResponse = $this->delete("/subjects/{$subject->id}");
        $delResponse->assertStatus(302);
        $this->assertNull(Subject::find($subject->id));
    }

    public function test_room_update_and_delete(): void
    {
        $room = Classroom::create([
            'name' => 'Temporary Lab',
            'room_type' => 'Computer Lab',
            'capacity' => 30,
            'building' => 'Old Wing',
        ]);

        $updateResponse = $this->put("/rooms/{$room->id}", [
            'name' => 'Renovated Science Lab',
            'room_type' => 'Physics Lab',
            'capacity' => 40,
            'building' => 'New STEM Wing',
        ]);

        $updateResponse->assertStatus(302);
        $room->refresh();
        $this->assertEquals('Renovated Science Lab', $room->name);
        $this->assertEquals(40, $room->capacity);
        $this->assertEquals('New STEM Wing', $room->building);

        $delResponse = $this->delete("/rooms/{$room->id}");
        $delResponse->assertStatus(302);
        $this->assertNull(Classroom::find($room->id));
    }

    public function test_combined_cohort_session_in_mpr_and_synchronized_slot_move(): void
    {
        $shoaib = Teacher::where('name', 'like', '%Shoaib%')->first();
        $this->assertNotNull($shoaib);

        $counseling = Subject::firstOrCreate(
            ['name' => 'Counseling Session'],
            [
                'code' => 'CSL',
                'subject_type' => 'Theory',
                'requires_lab' => false,
                'default_room_type' => 'Multi-Purpose Room',
                'color' => '#14b8a6',
                'is_difficult' => false,
                'allowed_double_period' => false
            ]
        );

        $mpr = Classroom::firstOrCreate(
            ['name' => 'MPR'],
            [
                'room_type' => 'Multi-Purpose Room',
                'capacity' => 150,
                'building' => 'Main Academic Block'
            ]
        );

        $sec8A = Section::where('name', '8A')->first();
        $sec8B = Section::where('name', '8B')->first();
        $sec8C = Section::where('name', '8C')->first();

        $combinedGroupId = "COMBINED_CLASS_{$sec8A->grade_class_id}_SUB_{$counseling->id}";

        // Configure combined cohort session for all Class 8 sections in MPR
        foreach ([$sec8A, $sec8B, $sec8C] as $sec) {
            LessonAllocation::updateOrCreate(
                [
                    'section_id' => $sec->id,
                    'subject_id' => $counseling->id,
                ],
                [
                    'teacher_id' => $shoaib->id,
                    'room_id' => $mpr->id,
                    'periods_per_week' => 1,
                    'block_size' => 1,
                    'is_combined' => true,
                    'combined_group_id' => $combinedGroupId,
                    'sync_group_id' => $combinedGroupId,
                ]
            );
        }

        // Run CSP Solver
        $solver = new \App\Services\Solver\TimetableCspSolver(InstitutionSetting::getActive());
        $solveResult = $solver->solve();
        $this->assertTrue($solveResult['success']);

        // Verify all 3 sections are placed at the exact same day & period in MPR with Shoaib Rashid
        $slots = TimetableSlot::whereIn('section_id', [$sec8A->id, $sec8B->id, $sec8C->id])
            ->where('subject_id', $counseling->id)
            ->get();

        $this->assertCount(3, $slots);
        $firstSlot = $slots->first();
        $this->assertTrue($firstSlot->is_combined);
        $this->assertEquals($mpr->id, $firstSlot->room_id);
        $this->assertEquals($shoaib->id, $firstSlot->teacher_id);

        foreach ($slots as $s) {
            $this->assertEquals($firstSlot->day, $s->day);
            $this->assertEquals($firstSlot->period, $s->period);
            $this->assertEquals($shoaib->id, $s->teacher_id);
            $this->assertEquals($mpr->id, $s->room_id);
            $this->assertTrue($s->is_combined);
        }

        // Test moving 8A's slot moves 8B and 8C together
        // Find a destination day/period where the cohort can move (e.g., Saturday Period 2)
        // Clear slots on Saturday Period 2 for testing
        TimetableSlot::where('day', 'Saturday')->where('period', 2)->delete();

        $moveResponse = $this->postJson('/slot/update', [
            'slot_id' => $firstSlot->id,
            'day' => 'Saturday',
            'period' => 2,
            'teacher_id' => $shoaib->id,
            'room_id' => $mpr->id,
        ]);

        $moveResponse->assertStatus(200);
        $data = $moveResponse->json();
        $this->assertTrue($data['success']);
        $this->assertStringContainsString('Combined cohort session updated', $data['message']);

        // Verify all sibling sections moved to Saturday Period 2
        $updatedSlots = TimetableSlot::whereIn('section_id', [$sec8A->id, $sec8B->id, $sec8C->id])
            ->where('subject_id', $counseling->id)
            ->get();

        foreach ($updatedSlots as $us) {
            $this->assertEquals('Saturday', $us->day);
            $this->assertEquals(2, $us->period);
            $this->assertEquals($shoaib->id, $us->teacher_id);
            $this->assertEquals($mpr->id, $us->room_id);
        }

        // Verify Teacher View aggregates sections
        $teacherViewResponse = $this->get('/timetable?mode=teacher&id=' . $shoaib->id);
        $teacherViewResponse->assertStatus(200);

        // Verify Room View aggregates sections
        $roomViewResponse = $this->get('/timetable?mode=room&id=' . $mpr->id);
        $roomViewResponse->assertStatus(200);
    }
}
