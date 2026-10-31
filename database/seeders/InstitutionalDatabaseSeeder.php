<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstitutionSetting;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\GradeClass;
use App\Models\Classroom;
use App\Models\Section;
use App\Models\LessonAllocation;
use App\Models\DutyType;
use App\Models\DutySlot;
use App\Models\DutyAssignment;
use App\Models\TimetableSlot;

class InstitutionalDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Institution Setting
        InstitutionSetting::query()->delete();
        InstitutionSetting::create([
            'name' => 'Government Higher Secondary School & College',
            'academic_year' => '2026-2027',
            'term' => 'Annual Term',
            'motto' => 'Excellence in Education, Character & Leadership',
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'periods_per_day' => 7,
            'friday_periods' => 4,
            'day_periods' => [
                'Monday' => 7,
                'Tuesday' => 7,
                'Wednesday' => 7,
                'Thursday' => 7,
                'Friday' => 4,
                'Saturday' => 6,
            ],
            'break_after_period' => 4,
            'break_duration_mins' => 30,
            'period_duration_mins' => 45,
            'start_hour' => 8,
            'start_min' => 0,
        ]);

        // 2. Subjects
        Subject::query()->delete();
        $subjectDefs = [
            ['name' => 'English Language & Comp', 'code' => 'ENG', 'subject_type' => 'Theory', 'requires_lab' => false, 'default_room_type' => 'General Classroom', 'color' => '#2563eb', 'is_difficult' => false, 'allowed_double_period' => false],
            ['name' => 'Urdu Literature', 'code' => 'URDU', 'subject_type' => 'Theory', 'requires_lab' => false, 'default_room_type' => 'General Classroom', 'color' => '#059669', 'is_difficult' => false, 'allowed_double_period' => false],
            ['name' => 'Mathematics', 'code' => 'MATH', 'subject_type' => 'Theory', 'requires_lab' => false, 'default_room_type' => 'General Classroom', 'color' => '#7c3aed', 'is_difficult' => true, 'allowed_double_period' => false],
            ['name' => 'Physics', 'code' => 'PHY', 'subject_type' => 'Practical Lab', 'requires_lab' => true, 'default_room_type' => 'Physics Lab', 'color' => '#d97706', 'is_difficult' => true, 'allowed_double_period' => true],
            ['name' => 'Chemistry', 'code' => 'CHEM', 'subject_type' => 'Practical Lab', 'requires_lab' => true, 'default_room_type' => 'Chemistry Lab', 'color' => '#db2777', 'is_difficult' => true, 'allowed_double_period' => true],
            ['name' => 'Biology', 'code' => 'BIO', 'subject_type' => 'Practical Lab', 'requires_lab' => true, 'default_room_type' => 'Biology Lab', 'color' => '#0891b2', 'is_difficult' => false, 'allowed_double_period' => true],
            ['name' => 'Information Technology', 'code' => 'IT', 'subject_type' => 'Practical Lab', 'requires_lab' => true, 'default_room_type' => 'Computer Lab', 'color' => '#4f46e5', 'is_difficult' => false, 'allowed_double_period' => true],
            ['name' => 'Social Studies / Pak Studies', 'code' => 'SST', 'subject_type' => 'Theory', 'requires_lab' => false, 'default_room_type' => 'General Classroom', 'color' => '#0d9488', 'is_difficult' => false, 'allowed_double_period' => false],
            ['name' => 'Islamic Studies & Quran', 'code' => 'ISL', 'subject_type' => 'Theory', 'requires_lab' => false, 'default_room_type' => 'General Classroom', 'color' => '#65a30d', 'is_difficult' => false, 'allowed_double_period' => false],
            ['name' => 'Fine Arts & Drawing', 'code' => 'ART', 'subject_type' => 'Practical Lab', 'requires_lab' => false, 'default_room_type' => 'Fine Arts Studio', 'color' => '#ea580c', 'is_difficult' => false, 'allowed_double_period' => true],
            ['name' => 'Central Library & Reading', 'code' => 'LIB', 'subject_type' => 'Theory', 'requires_lab' => false, 'default_room_type' => 'Central Library', 'color' => '#475569', 'is_difficult' => false, 'allowed_double_period' => false],
        ];

        $subjects = [];
        foreach ($subjectDefs as $sDef) {
            $sub = Subject::create($sDef);
            $subjects[$sDef['code']] = $sub;
        }

        // 3. Classrooms & Specialized Labs
        Classroom::query()->delete();
        $rooms = [];
        $classes = [6, 7, 8, 9, 10, 11, 12];
        $sectionLetters = ['A', 'B', 'C'];
        foreach ($classes as $c) {
            foreach ($sectionLetters as $sec) {
                $rName = "Room {$c}{$sec}";
                $room = Classroom::create([
                    'name' => $rName,
                    'room_type' => 'General Classroom',
                    'capacity' => 45,
                    'building' => 'Academic Block ' . ($c <= 8 ? 'Junior' : ($c <= 10 ? 'Senior' : 'College')),
                ]);
                $rooms[$rName] = $room;
            }
        }

        // Specialized Labs
        $specializedLabs = [
            ['name' => 'Computer Lab', 'room_type' => 'Computer Lab', 'capacity' => 50, 'building' => 'IT Complex'],
            ['name' => 'Physics Lab', 'room_type' => 'Physics Lab', 'capacity' => 45, 'building' => 'Science Wing'],
            ['name' => 'Chemistry Lab', 'room_type' => 'Chemistry Lab', 'capacity' => 45, 'building' => 'Science Wing'],
            ['name' => 'Biology Lab', 'room_type' => 'Biology Lab', 'capacity' => 45, 'building' => 'Science Wing'],
            ['name' => 'Fine Arts Studio', 'room_type' => 'Fine Arts Studio', 'capacity' => 40, 'building' => 'Creative Arts Block'],
            ['name' => 'Central Library', 'room_type' => 'Central Library', 'capacity' => 60, 'building' => 'Administration Block'],
            ['name' => 'MPR', 'room_type' => 'Multi-Purpose Room', 'capacity' => 150, 'building' => 'Main Academic Block'],
        ];
        foreach ($specializedLabs as $sLab) {
            $room = Classroom::create($sLab);
            $rooms[$sLab['name']] = $room;
        }

        // 4. 43 Specialist Faculty Members
        Teacher::query()->delete();
        $facultyData = [
            // English (5)
            ['name' => 'Rizwan Ahmed', 'code' => 'RIZ', 'sub' => 'ENG', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Junaid Tariq', 'code' => 'JUN', 'sub' => 'ENG', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Barkaat Ali', 'code' => 'BAR', 'sub' => 'ENG', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Afzal Khan', 'code' => 'AFZ', 'sub' => 'ENG', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Yasir Mehmood', 'code' => 'YAS', 'sub' => 'ENG', 'max_w' => 24, 'max_d' => 5],

            // Urdu (5)
            ['name' => 'Kamran Shah', 'code' => 'KAM', 'sub' => 'URDU', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Faisal Iqbal', 'code' => 'FI', 'sub' => 'URDU', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Shahzad Aslam', 'code' => 'SHZ', 'sub' => 'URDU', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Ismail Farooq', 'code' => 'ISM', 'sub' => 'URDU', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Fayaz Hussain', 'code' => 'FAY', 'sub' => 'URDU', 'max_w' => 24, 'max_d' => 5],

            // Mathematics (6)
            ['name' => 'Faizan Haider', 'code' => 'FAZ', 'sub' => 'MATH', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Abdul Rauf', 'code' => 'ARF', 'sub' => 'MATH', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Mohsin Raza', 'code' => 'MOH', 'sub' => 'MATH', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Zawar Shah', 'code' => 'ZAW', 'sub' => 'MATH', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Ajmal Latif', 'code' => 'AJM', 'sub' => 'MATH', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Danish Munir', 'code' => 'DAN.M', 'sub' => 'MATH', 'max_w' => 24, 'max_d' => 5],

            // Physics (5)
            ['name' => 'Sajid Mehmood', 'code' => 'SAJ', 'sub' => 'PHY', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Syed Zaman', 'code' => 'SZ', 'sub' => 'PHY', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Irshad Ullah', 'code' => 'IRSH', 'sub' => 'PHY', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Danish Siddiqui', 'code' => 'DS', 'sub' => 'PHY', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Zulqurnain Haider', 'code' => 'ZUL', 'sub' => 'PHY', 'max_w' => 24, 'max_d' => 5],

            // Chemistry (5)
            ['name' => 'Bilal Hassan', 'code' => 'BH', 'sub' => 'CHEM', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Abdul Rehman Chem', 'code' => 'ARC', 'sub' => 'CHEM', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Ishaq Dar', 'code' => 'ISH', 'sub' => 'CHEM', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Usama Qasim', 'code' => 'USA', 'sub' => 'CHEM', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Mudasar Nawaz', 'code' => 'MUD', 'sub' => 'CHEM', 'max_w' => 24, 'max_d' => 5],

            // Biology (4)
            ['name' => 'Noman Bashir Bio', 'code' => 'NOM.B', 'sub' => 'BIO', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Nazneen Akhtar', 'code' => 'NAZ', 'sub' => 'BIO', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Bilal Chaudhry', 'code' => 'BC', 'sub' => 'BIO', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Umar Farooq Bio', 'code' => 'UMR', 'sub' => 'BIO', 'max_w' => 24, 'max_d' => 5],

            // Information Technology / C.O (3)
            ['name' => 'Adnan Tariq', 'code' => 'ADN', 'sub' => 'IT', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Shoaib Rashid', 'code' => 'SR', 'sub' => 'IT', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Noman IT Specialist', 'code' => 'NOM.IT', 'sub' => 'IT', 'max_w' => 24, 'max_d' => 5],

            // Social Studies / SST (3)
            ['name' => 'Zaheer Abbas', 'code' => 'ZAH', 'sub' => 'SST', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Akram Gill', 'code' => 'AKR', 'sub' => 'SST', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Abdul Rehman SST', 'code' => 'ARS', 'sub' => 'SST', 'max_w' => 24, 'max_d' => 5],

            // Islamic Studies & Quran (4)
            ['name' => 'Fazal Gulzar', 'code' => 'FG', 'sub' => 'ISL', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Imran Hussain', 'code' => 'IH', 'sub' => 'ISL', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Zakirullah Khan', 'code' => 'ZAK', 'sub' => 'ISL', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Abu Bakar', 'code' => 'AB', 'sub' => 'ISL', 'max_w' => 24, 'max_d' => 5],

            // Fine Arts, Library & General (3)
            ['name' => 'Naveed Akhtar', 'code' => 'NAV', 'sub' => 'ART', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Saleem Yousaf', 'code' => 'SAL', 'sub' => 'LIB', 'max_w' => 24, 'max_d' => 5],
            ['name' => 'Aamir Ali', 'code' => 'AA', 'sub' => 'ART', 'max_w' => 24, 'max_d' => 5],
        ];

        $teachers = [];
        $teacherByCode = [];
        $teachersBySubject = [];

        foreach ($facultyData as $f) {
            $primarySub = $subjects[$f['sub']];
            $t = Teacher::create([
                'name' => $f['name'],
                'code' => $f['code'],
                'email' => strtolower(str_replace('.', '', $f['code'])) . '@school.edu.pk',
                'phone' => '+92-300-' . rand(1111111, 9999999),
                'primary_subject_id' => $primarySub->id,
                'qualified_subjects' => [$primarySub->id],
                'max_periods_per_week' => $f['max_w'],
                'max_periods_per_day' => $f['max_d'],
                'unavailable_slots' => [],
            ]);
            $teachers[] = $t;
            $teacherByCode[$f['code']] = $t;
            $teachersBySubject[$f['sub']][] = $t;
        }

        // Add realistic sample unavailable slots for a few teachers (e.g., commute/leaves)
        $teacherByCode['RIZ']->addUnavailableSlot('Monday', 1);
        $teacherByCode['FAZ']->addUnavailableSlot('Wednesday', 7);
        $teacherByCode['ADN']->addUnavailableSlot('Thursday', 7);

        // 5. Classes & 21 Sections with Designated Incharges
        Section::query()->delete();
        GradeClass::query()->delete();

        $allSections = [];
        $sectionInchargeCodes = [
            '6A' => 'RIZ', '6B' => 'KAM', '6C' => 'FAZ',
            '7A' => 'SAJ', '7B' => 'BH',  '7C' => 'NOM.B',
            '8A' => 'ADN', '8B' => 'ZAH', '8C' => 'FG',
            '9A' => 'JUN', '9B' => 'FI',  '9C' => 'ARF',
            '10A' => 'SZ', '10B' => 'ARC','10C' => 'NAZ',
            '11A' => 'SR', '11B' => 'AKR','11C' => 'IH',
            '12A' => 'BAR','12B' => 'SHZ','12C' => 'MOH',
        ];

        foreach ($classes as $cNum) {
            $gClass = GradeClass::create([
                'name' => "Class {$cNum}",
                'order_num' => $cNum,
            ]);

            foreach ($sectionLetters as $secLetter) {
                $secName = "{$cNum}{$secLetter}";
                $homeRoom = $rooms["Room {$cNum}{$secLetter}"] ?? null;
                $inchargeCode = $sectionInchargeCodes[$secName] ?? null;
                $incharge = $inchargeCode ? ($teacherByCode[$inchargeCode] ?? null) : null;

                $sec = Section::create([
                    'grade_class_id' => $gClass->id,
                    'name' => $secName,
                    'student_count' => rand(32, 40),
                    'home_room_id' => $homeRoom?->id,
                    'class_teacher_id' => $incharge?->id,
                ]);
                $allSections[$secName] = $sec;
            }
        }

        // 6. 210 Curriculum Lessons: exactly 10 allocations per section * 21 sections = 210 allocations
        // Exactly 30 periods per section (32 weekly slots - 2 free periods)
        LessonAllocation::query()->delete();
        TimetableSlot::query()->delete();

        $teacherIndices = [];
        foreach ($teachersBySubject as $subCode => $tList) {
            $teacherIndices[$subCode] = 0;
        }

        $getNextTeacher = function (string $subCode) use (&$teacherIndices, $teachersBySubject) {
            $list = $teachersBySubject[$subCode] ?? [];
            if (empty($list)) return null;
            $idx = $teacherIndices[$subCode] % count($list);
            $teacherIndices[$subCode]++;
            return $list[$idx];
        };

        foreach ($allSections as $secName => $section) {
            $cNum = (int) preg_replace('/\D/', '', $secName);
            $secLetter = substr($secName, -1);
            $homeRoomId = $section->home_room_id;

            if ($cNum <= 8) {
                // Middle School (Classes 6, 7, 8):
                // For parallel alignment across sections A, B, C:
                // Section A attends Computer Lab
                // Section B attends Fine Arts Studio
                // Section C attends Central Library
                // Synchronized simultaneously!
                $allocations = [
                    ['sub' => 'ENG', 'periods' => 4, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'URDU', 'periods' => 4, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'MATH', 'periods' => 5, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'SST', 'periods' => 3, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'ISL', 'periods' => 3, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'IT', 'periods' => 2, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'IT', 'periods' => 2, 'block' => 2, 'room_id' => $rooms['Computer Lab']->id, 'sync' => ($secLetter === 'A' ? "SYNC_MID_{$cNum}_PARALLEL" : null)],
                    ['sub' => 'ART', 'periods' => 3, 'block' => 2, 'room_id' => $rooms['Fine Arts Studio']->id, 'sync' => ($secLetter === 'B' ? "SYNC_MID_{$cNum}_PARALLEL" : null)],
                    ['sub' => 'LIB', 'periods' => 2, 'block' => 2, 'room_id' => $rooms['Central Library']->id, 'sync' => ($secLetter === 'C' ? "SYNC_MID_{$cNum}_PARALLEL" : null)],
                    ['sub' => ($cNum == 6 ? 'PHY' : ($cNum == 7 ? 'CHEM' : 'BIO')), 'periods' => 2, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                ];
            } elseif ($cNum <= 10) {
                // Matriculation (Classes 9, 10):
                // Section A attends Physics Lab
                // Section B attends Chemistry Lab
                // Section C attends Biology Lab
                // Synchronized simultaneously!
                $allocations = [
                    ['sub' => 'ENG', 'periods' => 4, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'URDU', 'periods' => 4, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'MATH', 'periods' => 4, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'PHY', 'periods' => 2, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'PHY', 'periods' => 2, 'block' => 2, 'room_id' => $rooms['Physics Lab']->id, 'sync' => ($secLetter === 'A' ? "SYNC_{$cNum}_SCIENCE_LAB" : null)],
                    ['sub' => 'CHEM', 'periods' => 2, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'CHEM', 'periods' => 2, 'block' => 2, 'room_id' => $rooms['Chemistry Lab']->id, 'sync' => ($secLetter === 'B' ? "SYNC_{$cNum}_SCIENCE_LAB" : null)],
                    ['sub' => 'BIO', 'periods' => 2, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'BIO', 'periods' => 2, 'block' => 2, 'room_id' => $rooms['Biology Lab']->id, 'sync' => ($secLetter === 'C' ? "SYNC_{$cNum}_SCIENCE_LAB" : null)],
                    ['sub' => 'ISL', 'periods' => 6, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                ];
            } else {
                // College / HSSC (Classes 11, 12):
                // Section A attends Physics Lab
                // Section B attends Chemistry Lab
                // Section C attends Biology Lab
                // Synchronized simultaneously!
                $allocations = [
                    ['sub' => 'ENG', 'periods' => 4, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'URDU', 'periods' => 4, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'MATH', 'periods' => 4, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'PHY', 'periods' => 3, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'PHY', 'periods' => 2, 'block' => 2, 'room_id' => $rooms['Physics Lab']->id, 'sync' => ($secLetter === 'A' ? "SYNC_{$cNum}_SCIENCE_LAB" : null)],
                    ['sub' => 'CHEM', 'periods' => 3, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'CHEM', 'periods' => 2, 'block' => 2, 'room_id' => $rooms['Chemistry Lab']->id, 'sync' => ($secLetter === 'B' ? "SYNC_{$cNum}_SCIENCE_LAB" : null)],
                    ['sub' => 'BIO', 'periods' => 3, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                    ['sub' => 'BIO', 'periods' => 2, 'block' => 2, 'room_id' => $rooms['Biology Lab']->id, 'sync' => ($secLetter === 'C' ? "SYNC_{$cNum}_SCIENCE_LAB" : null)],
                    ['sub' => 'ISL', 'periods' => 3, 'block' => 1, 'room_id' => $homeRoomId, 'sync' => null],
                ];
            }

            foreach ($allocations as $a) {
                $sub = $subjects[$a['sub']];
                $teacher = $getNextTeacher($a['sub']);

                LessonAllocation::create([
                    'section_id' => $section->id,
                    'subject_id' => $sub->id,
                    'teacher_id' => $teacher->id,
                    'room_id' => $a['room_id'],
                    'periods_per_week' => $a['periods'],
                    'block_size' => $a['block'],
                    'sync_group_id' => $a['sync'],
                ]);
            }
        }

        // 7. Campus Duty Roster
        DutyAssignment::query()->delete();
        DutySlot::query()->delete();
        DutyType::query()->delete();

        $dTypes = [
            ['name' => 'Morning Assembly Supervision', 'description' => 'Discipline and student queue monitoring at general assembly', 'color' => '#3b82f6'],
            ['name' => 'Main Gate & Perimeter Duty', 'description' => 'Morning arrival and afternoon departure gate attendance', 'color' => '#10b981'],
            ['name' => 'Recess Corridor Monitoring', 'description' => 'Monitoring cafeteria, ground, and corridors during break', 'color' => '#f59e0b'],
            ['name' => 'Exam Hall Invigilation', 'description' => 'Periodic test invigilation and assessment proctoring', 'color' => '#8b5cf6'],
        ];

        $dutyTypesCreated = [];
        foreach ($dTypes as $dt) {
            $dutyTypesCreated[$dt['name']] = DutyType::create($dt);
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ($days as $day) {
            DutySlot::create([
                'duty_type_id' => $dutyTypesCreated['Morning Assembly Supervision']->id,
                'day' => $day,
                'period' => 0,
                'location' => 'Central Assembly Ground',
                'required_teachers' => 3,
            ]);

            DutySlot::create([
                'duty_type_id' => $dutyTypesCreated['Main Gate & Perimeter Duty']->id,
                'day' => $day,
                'period' => -1,
                'location' => 'Main Entrance Gate',
                'required_teachers' => 2,
            ]);

            DutySlot::create([
                'duty_type_id' => $dutyTypesCreated['Recess Corridor Monitoring']->id,
                'day' => $day,
                'period' => 99,
                'location' => 'Corridors & Cafeteria',
                'required_teachers' => 3,
            ]);
        }
    }
}
