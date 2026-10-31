<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Master Institutional Matrix</title>
    <style>
        @page {
            margin: 10mm 10mm 10mm 10mm;
            size: A3 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5px;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }
        .page {
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .inst-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .inst-sub {
            font-size: 10px;
            color: #64748b;
        }
        .day-banner {
            background: #1e3a8a;
            color: #ffffff;
            font-size: 13px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 3px;
            margin-bottom: 8px;
            display: inline-block;
        }
        table.matrix {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.matrix th, table.matrix td {
            border: 1px solid #cbd5e1;
            padding: 4px 3px;
            text-align: center;
        }
        table.matrix th {
            background: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 9px;
        }
        .sec-col {
            background: #f8fafc;
            font-weight: bold;
            width: 55px;
            color: #1e3a8a;
            font-size: 9.5px;
        }
        .cell-content {
            border-radius: 2px;
            padding: 2px 1px;
            color: #ffffff;
            font-size: 8px;
            line-height: 1.1;
        }
        .cell-sub {
            font-weight: bold;
        }
        .cell-teacher {
            font-size: 7.5px;
            opacity: 0.95;
        }
        .cell-room {
            font-size: 7px;
            opacity: 0.9;
        }
        .empty-cell {
            color: #cbd5e1;
            font-size: 7.5px;
        }
        .recess-col {
            background: #fef3c7;
            color: #92400e;
            font-weight: bold;
            font-size: 8px;
            width: 25px;
        }
        .footer-table {
            width: 100%;
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
            font-size: 8px;
            color: #64748b;
        }
    </style>
</head>
<body>
    @foreach($workingDays as $day)
        @php
            $dayLimit = $setting->getPeriodsForDay($day);
        @endphp
        <div class="page">
            <div class="header">
                <div class="inst-title">{{ $setting->name }}</div>
                <div class="inst-sub">PANORAMIC MASTER INSTITUTIONAL SCHEDULE | ACADEMIC YEAR: {{ $setting->academic_year }}</div>
            </div>

            <div style="margin-bottom: 6px;">
                <span class="day-banner">{{ strtoupper($day) }} TIMETABLE</span>
                @if($day === 'Friday')
                    <span style="background: #ef4444; color: #fff; font-weight: bold; padding: 4px 10px; border-radius: 3px; font-size: 11px; margin-left: 10px;">
                        ⚠️ Early Dismissal after Period {{ $dayLimit }} (Jummah Prayer)
                    </span>
                @else
                    <span style="color: #64748b; font-size: 10px; margin-left: 10px;">
                        Standard Day: {{ $dayLimit }} Periods
                    </span>
                @endif
            </div>

            <table class="matrix">
                <thead>
                    <tr>
                        <th style="width: 60px;">Section</th>
                        <th style="width: 100px;">Class Incharge</th>
                        @for($p = 1; $p <= $dayLimit; $p++)
                            @if($p === ($setting->break_after_period + 1))
                                <th class="recess-col">RECESS</th>
                            @endif
                            <th>
                                Period {{ $p }}<br/>
                                <span style="font-size: 7.5px; font-weight: normal; opacity: 0.9;">
                                    {{ $periodTimes[$p]['start_formatted'] ?? '' }} - {{ $periodTimes[$p]['end_formatted'] ?? '' }}
                                </span>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $section)
                        @php
                            $incharge = $section->classTeacher;
                        @endphp
                        <tr>
                            <td class="sec-col">{{ $section->name }}</td>
                            <td style="font-size: 8px; text-align: left; padding-left: 5px; color: #334155;">
                                {{ $incharge ? "{$incharge->name} ({$incharge->code})" : '—' }}
                            </td>
                            @for($p = 1; $p <= $dayLimit; $p++)
                                @if($p === ($setting->break_after_period + 1))
                                    <td class="recess-col">BREAK</td>
                                @endif
                                @php
                                    $slot = $matrix[$day][$section->id][$p] ?? null;
                                @endphp
                                <td>
                                    @if($slot)
                                        <div class="cell-content" style="background-color: {{ $slot->subject->color ?? '#3b82f6' }};">
                                            <div class="cell-sub">{{ $slot->subject->code }}</div>
                                            <div class="cell-teacher">{{ $slot->teacher->code }}</div>
                                            <div class="cell-room">{{ $slot->room->name }}</div>
                                        </div>
                                    @else
                                        <span class="empty-cell">Free</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="footer-table">
                <tr>
                    <td>Panoramic Master Institutional Matrix — Generated by Laravel CSP Constraint Solver Engine</td>
                    <td style="text-align: right;">Page {{ $loop->iteration }} of {{ count($workingDays) }}</td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>
