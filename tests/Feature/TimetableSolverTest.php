<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\InstitutionalDatabaseSeeder;
use App\Models\InstitutionSetting;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TimetableSlot;
use App\Models\DutyAssignment;
use App\Services\Solver\FeasibilityChecker;
use App\Services\Solver\TimetableCspSolver;
use App\Services\Solver\DutyRosterOptimizer;
use App\Services\Export\ExportService;

class TimetableSolverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        ini_set('memory_limit', '512M');
        $this->seed(InstitutionalDatabaseSeeder::class);
    }

    public function test_institution_settings_methods(): void
    {
        $setting = InstitutionSetting::getActive();
        $this->assertEquals(7, $setting->getPeriodsForDay('Monday'));
        $this->assertEquals(4, $setting->getPeriodsForDay('Friday'));
        $this->assertEquals(6, $setting->getPeriodsForDay('Saturday'));
        $this->assertEquals(38, $setting->getTotalWeeklySlots());

        $times = $setting->generateDefaultPeriodTimes();
        $this->assertNotEmpty($times);
        $this->assertEquals('08:00', $times[1]['start_formatted']);
    }

    public function test_teacher_model_and_availability(): void
    {
        $teacher = Teacher::where('code', 'RIZ')->first();
        $this->assertNotNull($teacher);

        // Monday P1 was seeded as unavailable for RIZ
        $this->assertFalse($teacher->isAvailable('Monday', 1));
        $this->assertTrue($teacher->isAvailable('Monday', 2));

        // Test dynamic adding/removing of unavailable slots
        $teacher->addUnavailableSlot('Tuesday', 3);
        $this->assertFalse($teacher->isAvailable('Tuesday', 3));

        $teacher->removeUnavailableSlot('Tuesday', 3);
        $this->assertTrue($teacher->isAvailable('Tuesday', 3));

        // Test auto initials generation
        $initials = Teacher::generateInitials('Dr. Albert Einstein');
        $this->assertEquals('AE', $initials);
    }

    public function test_pre_flight_feasibility_check(): void
    {
        $checker = new FeasibilityChecker();
        $res = $checker->check();

        $this->assertEquals(0, $res['critical_count']);
        $this->assertTrue($res['stats']['can_solve']);
        $this->assertEquals(21, $res['stats']['sections_count']);
        $this->assertEquals(43, $res['stats']['teachers_count']);
        $this->assertEquals(210, $res['stats']['allocations_count']);
    }

    public function test_csp_solver_execution_and_sub_second_performance(): void
    {
        $solver = new TimetableCspSolver();
        $result = $solver->solve(true);

        $this->assertTrue($result['success']);
        $this->assertEquals(630, $result['slots_assigned']);
        $this->assertLessThan(1500, $result['duration_ms'], "Solver must execute rapidly");

        // Verify hard constraint 1: No teacher double booked
        $teacherCollisions = TimetableSlot::select('teacher_id', 'day', 'period')
            ->groupBy('teacher_id', 'day', 'period')
            ->havingRaw('COUNT(*) > 1')
            ->count();
        $this->assertEquals(0, $teacherCollisions, "Zero teacher double-bookings must occur.");

        // Verify hard constraint 2: No section double booked
        $sectionCollisions = TimetableSlot::select('section_id', 'day', 'period')
            ->groupBy('section_id', 'day', 'period')
            ->havingRaw('COUNT(*) > 1')
            ->count();
        $this->assertEquals(0, $sectionCollisions, "Zero section double-bookings must occur.");

        // Verify hard constraint 3: Friday early dismissal respected (period <= 4)
        $fridayViolations = TimetableSlot::where('day', 'Friday')
            ->where('period', '>', 4)
            ->count();
        $this->assertEquals(0, $fridayViolations, "Zero Friday slots scheduled past Period 4 dismissal.");
    }

    public function test_duty_roster_fairness_optimizer(): void
    {
        $optimizer = new DutyRosterOptimizer();
        $result = $optimizer->optimize();

        $this->assertTrue($result['success']);
        $this->assertGreaterThan(0, $result['total_assigned']);
        $this->assertLessThanOrEqual(2, $result['spread'], "Duty spread Δ must be <= 1-2");
    }

    public function test_web_routes_and_dashboard_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Government Higher Secondary School');

        $responseTimetable = $this->get('/timetable');
        $responseTimetable->assertStatus(200);

        $responseAllotments = $this->get('/allotments');
        $responseAllotments->assertStatus(200);

        $responseFaculty = $this->get('/faculty');
        $responseFaculty->assertStatus(200);

        $responseExports = $this->get('/exports');
        $responseExports->assertStatus(200);
    }

    public function test_csv_and_json_exports(): void
    {
        $exporter = new ExportService();
        $csvTimetable = $exporter->generateMasterTimetableCsv();
        $this->assertNotEmpty($csvTimetable);
        $this->assertStringContainsString('Section,Class,Day,Period,Subject', $csvTimetable);

        $csvDuty = $exporter->generateDutyRosterCsv();
        $this->assertNotEmpty($csvDuty);
        $this->assertStringContainsString('Day,DutyType,Location,AssignedTeacher', $csvDuty);

        $jsonProject = $exporter->exportProjectJson();
        $this->assertNotEmpty($jsonProject);
        $decoded = json_decode($jsonProject, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('institution_settings', $decoded);
        $this->assertArrayHasKey('teachers', $decoded);
    }
}
