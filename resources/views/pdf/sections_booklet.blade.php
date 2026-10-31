<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Class & Section Timetable Booklet</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: A4 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .page {
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .inst-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .inst-sub {
            font-size: 10px;
            color: #64748b;
        }
        .section-badge {
            font-size: 14px;
            font-weight: bold;
            background: #2563eb;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 4px;
            text-align: right;
            display: inline-block;
        }
        .meta-bar {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 10px;
        }
        .meta-item {
            display: inline-block;
            margin-right: 20px;
        }
        .meta-label {
            font-weight: bold;
            color: #475569;
        }
        table.grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.grid th, table.grid td {
            border: 1px solid #cbd5e1;
            padding: 6px 4px;
            text-align: center;
        }
        table.grid th {
            background: #1e293b;
            color: #ffffff;
            font-weight: 600;
            font-size: 9.5px;
        }
        .day-col {
            background: #f8fafc;
            font-weight: bold;
            color: #0f172a;
            width: 80px;
        }
        .slot-card {
            padding: 3px;
            border-radius: 3px;
            font-size: 9px;
            line-height: 1.2;
            color: #ffffff;
            font-weight: 500;
        }
        .slot-sub {
            font-weight: bold;
            font-size: 9.5px;
        }
        .slot-teacher {
            font-size: 8.5px;
            opacity: 0.95;
        }
        .slot-room {
            font-size: 8px;
            opacity: 0.9;
        }
        .empty-slot {
            color: #94a3b8;
            font-style: italic;
            font-size: 8.5px;
        }
        .dismissal-slot {
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .recess-col {
            background: #fef3c7;
            color: #92400e;
            font-weight: bold;
            font-size: 8px;
            writing-mode: vertical-rl;
            text-align: center;
            width: 25px;
        }
        .legend-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            margin-top: 10px;
        }
        .legend-table td {
            border: 1px solid #e2e8f0;
            padding: 3px 6px;
        }
        .footer-table {
            width: 100%;
            margin-top: 15px;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
            font-size: 8.5px;
            color: #64748b;
        }
    </style>
</head>
<body>
    @foreach($sections as $section)
        @php
            $incharge = $section->classTeacher;
            $homeRoom = $section->homeRoom;
            $slots = $timetable[$section->id] ?? [];
        @endphp
        <div class="page">
            <table class="header-table">
                <tr>
                    <td style="width: 70%;">
                        <div class="inst-title">{{ $setting->name }}</div>
                        <div class="inst-sub">{{ $setting->motto }} | Academic Year: {{ $setting->academic_year }} ({{ $setting->term }})</div>
                    </td>
                    <td style="width: 30%; text-align: right;">
                        <span class="section-badge">SECTION {{ $section->name }}</span>
                    </td>
                </tr>
            </table>

            <div class="meta-bar">
                <span class="meta-item"><span class="meta-label">Class Level:</span> {{ $section->gradeClass->name }}</span>
                <span class="meta-item"><span class="meta-label">Student Strength:</span> {{ $section->student_count }} Students</span>
                <span class="meta-item"><span class="meta-label">Base Classroom:</span> {{ $homeRoom ? $homeRoom->name : 'N/A' }}</span>
                <span class="meta-item"><span class="meta-label">👨‍🏫 Class Incharge:</span> <strong>{{ $incharge ? "{$incharge->name} ({$incharge->code})" : 'Unassigned' }}</strong></span>
                <span class="meta-item"><span class="meta-label">Weekly Lessons:</span> {{ $section->total_allotted_periods }} / {{ $setting->getTotalWeeklySlots() }} Periods</span>
            </div>

            <table class="grid">
                <thead>
                    <tr>
                        <th style="width: 75px;">Day / Time</th>
                        @for($p = 1; $p <= $maxPeriods; $p++)
                            @if($p === ($setting->break_after_period + 1))
                                <th style="width: 25px; background: #d97706;">RECESS</th>
                            @endif
                            <th>
                                Period {{ $p }}<br/>
                                <span style="font-size: 8px; font-weight: normal; opacity: 0.9;">
                                    {{ $periodTimes[$p]['start_formatted'] ?? '' }} - {{ $periodTimes[$p]['end_formatted'] ?? '' }}
                                </span>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($workingDays as $day)
                        @php
                            $dayLimit = $setting->getPeriodsForDay($day);
                        @endphp
                        <tr>
                            <td class="day-col">{{ $day }}</td>
                            @for($p = 1; $p <= $maxPeriods; $p++)
                                @if($p === ($setting->break_after_period + 1))
                                    <td class="recess-col">BREAK</td>
                                @endif

                                @if($p > $dayLimit)
                                    <td class="dismissal-slot">— Early Dismissal —</td>
                                @else
                                    @php
                                        $slot = $slots[$day][$p] ?? null;
                                    @endphp
                                    <td>
                                        @if($slot)
                                            <div class="slot-card" style="background-color: {{ $slot->subject->color ?? '#3b82f6' }};">
                                                <div class="slot-sub">{{ $slot->subject->code }}</div>
                                                <div class="slot-teacher">{{ $slot->teacher->code }} ({{ $slot->teacher->name }})</div>
                                                <div class="slot-room">{{ $slot->room->name }}</div>
                                            </div>
                                        @else
                                            <span class="empty-slot">Study Period</span>
                                        @endif
                                    </td>
                                @endif
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="footer-table">
                <tr>
                    <td style="width: 50%;">Generated by Institutional Timetable & Duty Solver (Laravel CSP Engine)</td>
                    <td style="width: 50%; text-align: right;">Class Incharge Signature: _______________________ &nbsp; Principal: _______________________</td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>
