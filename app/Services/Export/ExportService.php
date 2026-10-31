<?php

namespace App\Services\Export;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InstitutionSetting;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\TimetableSlot;
use App\Models\DutySlot;
use App\Models\DutyAssignment;
use App\Models\DutyType;
use App\Models\LessonAllocation;
use App\Models\GradeClass;
use Illuminate\Support\Facades\File;

class ExportService
{
    protected InstitutionSetting $setting;

    public function __construct(?InstitutionSetting $setting = null)
    {
        $this->setting = $setting ?? InstitutionSetting::getActive();
    }

    public function exportAllToDirectory(string $dir): array
    {
        ini_set('memory_limit', '512M');

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $results = [];

        // 1. Class & Section Timetable Booklet
        $secPdf = $this->generateSectionsBookletPdf();
        $secPath = $dir . DIRECTORY_SEPARATOR . 'Class_Sections_Timetable_Booklet.pdf';
        File::put($secPath, $secPdf);
        $results['sections_booklet'] = $secPath;

        // 2. Faculty Timetable Booklet
        $facPdf = $this->generateFacultyBookletPdf();
        $facPath = $dir . DIRECTORY_SEPARATOR . 'Faculty_Teachers_Timetable_Booklet.pdf';
        File::put($facPath, $facPdf);
        $results['faculty_booklet'] = $facPath;

        // 3. Master Institutional Matrix
        $matPdf = $this->generateMasterMatrixPdf();
        $matPath = $dir . DIRECTORY_SEPARATOR . 'Master_Institutional_Matrix.pdf';
        File::put($matPath, $matPdf);
        $results['master_matrix'] = $matPath;

        // 4. Staff Duty Roster
        $dutyPdf = $this->generateDutyRosterPdf();
        $dutyPath = $dir . DIRECTORY_SEPARATOR . 'Staff_Duty_Roster.pdf';
        File::put($dutyPath, $dutyPdf);
        $results['duty_roster'] = $dutyPath;

        // 5. Subject Schedules Report
        $subPdf = $this->generateSubjectSchedulesPdf();
        $subPath = $dir . DIRECTORY_SEPARATOR . 'Subject_Schedules_Report.pdf';
        File::put($subPath, $subPdf);
        $results['subject_schedules'] = $subPath;

        // 6. CSV Exports
        $csvMat = $this->generateMasterTimetableCsv();
        $csvMatPath = $dir . DIRECTORY_SEPARATOR . 'Master_Timetable.csv';
        File::put($csvMatPath, $csvMat);
        $results['csv_timetable'] = $csvMatPath;

        $csvDuty = $this->generateDutyRosterCsv();
        $csvDutyPath = $dir . DIRECTORY_SEPARATOR . 'Staff_Duty_Roster.csv';
        File::put($csvDutyPath, $csvDuty);
        $results['csv_duty'] = $csvDutyPath;

        // 7. Full Human-Readable JSON Project Export
        $jsonProject = $this->exportProjectJson();
        $jsonPath = $dir . DIRECTORY_SEPARATOR . 'timetable_project.json';
        File::put($jsonPath, $jsonProject);
        $results['json_project'] = $jsonPath;

        return $results;
    }

    public function generateSectionsBookletPdf(): string
    {
        $sections = Section::with(['gradeClass', 'homeRoom', 'classTeacher', 'lessonAllocations.subject'])->orderBy('name')->get();
        $slots = TimetableSlot::with(['subject', 'teacher', 'room'])->get();

        $timetable = [];
        foreach ($slots as $s) {
            $timetable[$s->section_id][$s->day][$s->period] = $s;
        }

        $pdf = Pdf::loadView('pdf.sections_booklet', [
            'setting' => $this->setting,
            'sections' => $sections,
            'timetable' => $timetable,
            'workingDays' => $this->setting->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'maxPeriods' => $this->setting->getMaxPeriodsAnyDay(),
            'periodTimes' => $this->setting->generateDefaultPeriodTimes(),
        ]);

        $pdf->setPaper('a4', 'landscape');
        return $pdf->output();
    }

    public function generateFacultyBookletPdf(): string
    {
        $teachers = Teacher::with(['primarySubject', 'inchargeSection'])->orderBy('name')->get();
        $slots = TimetableSlot::with(['subject', 'section', 'room'])->get();

        $timetable = [];
        foreach ($slots as $s) {
            $timetable[$s->teacher_id][$s->day][$s->period] = $s;
        }

        $dutyAssignments = DutyAssignment::with(['slot.dutyType'])->get();
        $duties = [];
        foreach ($dutyAssignments as $da) {
            $duties[$da->teacher_id][] = $da;
        }

        $pdf = Pdf::loadView('pdf.faculty_booklet', [
            'setting' => $this->setting,
            'teachers' => $teachers,
            'timetable' => $timetable,
            'duties' => $duties,
            'workingDays' => $this->setting->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'maxPeriods' => $this->setting->getMaxPeriodsAnyDay(),
            'periodTimes' => $this->setting->generateDefaultPeriodTimes(),
        ]);

        $pdf->setPaper('a4', 'landscape');
        return $pdf->output();
    }

    public function generateMasterMatrixPdf(): string
    {
        $sections = Section::with(['classTeacher'])->orderBy('name')->get();
        $slots = TimetableSlot::with(['subject', 'teacher', 'room'])->get();

        $matrix = [];
        foreach ($slots as $s) {
            $matrix[$s->day][$s->section_id][$s->period] = $s;
        }

        $pdf = Pdf::loadView('pdf.master_matrix', [
            'setting' => $this->setting,
            'sections' => $sections,
            'matrix' => $matrix,
            'workingDays' => $this->setting->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'periodTimes' => $this->setting->generateDefaultPeriodTimes(),
        ]);

        $pdf->setPaper('a3', 'landscape');
        return $pdf->output();
    }

    public function generateDutyRosterPdf(): string
    {
        $dutySlots = DutySlot::with(['dutyType', 'assignments.teacher'])->orderBy('day')->orderBy('period')->get();
        $dutiesByDay = [];
        foreach ($dutySlots as $ds) {
            $dutiesByDay[$ds->day][] = $ds;
        }

        $teachers = Teacher::with('primarySubject')->orderBy('name')->get();
        $allAssignments = DutyAssignment::all();
        $teacherCounts = [];
        foreach ($allAssignments as $a) {
            $teacherCounts[$a->teacher_id] = ($teacherCounts[$a->teacher_id] ?? 0) + 1;
        }

        $facultyLoadAudit = [];
        foreach ($teachers as $t) {
            $c = $teacherCounts[$t->id] ?? 0;
            $facultyLoadAudit[] = [
                'code' => $t->code,
                'name' => $t->name,
                'subject' => $t->primarySubject?->name ?? 'General',
                'count' => $c,
            ];
        }

        $counts = array_column($facultyLoadAudit, 'count');
        $minLoad = !empty($counts) ? min($counts) : 0;
        $maxLoad = !empty($counts) ? max($counts) : 0;
        $spread = $maxLoad - $minLoad;

        $pdf = Pdf::loadView('pdf.duty_roster', [
            'setting' => $this->setting,
            'dutiesByDay' => $dutiesByDay,
            'totalDuties' => count($allAssignments),
            'totalTeachers' => count($teachers),
            'minLoad' => $minLoad,
            'maxLoad' => $maxLoad,
            'spread' => $spread,
            'facultyLoadAudit' => $facultyLoadAudit,
        ]);

        $pdf->setPaper('a4', 'portrait');
        return $pdf->output();
    }

    public function generateSubjectSchedulesPdf(): string
    {
        $subjects = Subject::orderBy('name')->get();
        $slots = TimetableSlot::with(['section', 'teacher', 'room'])->orderBy('day')->orderBy('period')->get();

        $timetableBySubject = [];
        foreach ($slots as $s) {
            $timetableBySubject[$s->subject_id][] = $s;
        }

        $pdf = Pdf::loadView('pdf.subject_schedules', [
            'setting' => $this->setting,
            'subjects' => $subjects,
            'timetableBySubject' => $timetableBySubject,
        ]);

        $pdf->setPaper('a4', 'portrait');
        return $pdf->output();
    }

    public function generateMasterTimetableCsv(): string
    {
        $slots = TimetableSlot::with(['section.gradeClass', 'subject', 'teacher', 'room'])
            ->orderBy('section_id')
            ->orderBy('day')
            ->orderBy('period')
            ->get();

        $f = fopen('php://memory', 'r+');
        fputcsv($f, ['Section', 'Class', 'Day', 'Period', 'Subject', 'SubjectCode', 'Teacher', 'TeacherCode', 'Room', 'RoomType']);

        foreach ($slots as $s) {
            fputcsv($f, [
                $s->section->name,
                $s->section->gradeClass->name,
                $s->day,
                $s->period,
                $s->subject->name,
                $s->subject->code,
                $s->teacher->name,
                $s->teacher->code,
                $s->room->name,
                $s->room->room_type,
            ]);
        }

        rewind($f);
        $csv = stream_get_contents($f);
        fclose($f);
        return $csv;
    }

    public function generateDutyRosterCsv(): string
    {
        $assignments = DutyAssignment::with(['slot.dutyType', 'teacher.primarySubject'])->get();

        $f = fopen('php://memory', 'r+');
        fputcsv($f, ['Day', 'DutyType', 'Location', 'AssignedTeacher', 'TeacherCode', 'PrimarySpecialization']);

        foreach ($assignments as $a) {
            fputcsv($f, [
                $a->slot->day,
                $a->slot->dutyType->name,
                $a->slot->location,
                $a->teacher->name,
                $a->teacher->code,
                $a->teacher->primarySubject?->name ?? 'General',
            ]);
        }

        rewind($f);
        $csv = stream_get_contents($f);
        fclose($f);
        return $csv;
    }

    public function exportProjectJson(): string
    {
        $data = [
            'exported_at' => now()->toIso8601String(),
            'version' => '1.0',
            'institution_settings' => InstitutionSetting::getActive()->toArray(),
            'subjects' => Subject::all()->toArray(),
            'teachers' => Teacher::all()->toArray(),
            'grade_classes' => GradeClass::all()->toArray(),
            'classrooms' => Classroom::all()->toArray(),
            'sections' => Section::all()->toArray(),
            'lesson_allocations' => LessonAllocation::all()->toArray(),
            'duty_types' => DutyType::all()->toArray(),
            'duty_slots' => DutySlot::all()->toArray(),
            'duty_assignments' => DutyAssignment::all()->toArray(),
            'timetable_slots' => TimetableSlot::all()->toArray(),
        ];

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
