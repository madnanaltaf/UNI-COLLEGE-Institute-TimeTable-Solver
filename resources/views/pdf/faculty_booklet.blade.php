<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Faculty Timetable Booklet</title>
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
            border-bottom: 2px solid #059669;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .inst-title {
            font-size: 16px;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
        }
        .inst-sub {
            font-size: 10px;
            color: #64748b;
        }
        .faculty-badge {
            font-size: 14px;
            font-weight: bold;
            background: #059669;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 4px;
            display: inline-block;
        }
        .meta-bar {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
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
            color: #166534;
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
            background: #0f172a;
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
        .empty-slot {
            color: #94a3b8;
            font-size: 8.5px;
        }
        .dismissal-slot {
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 8px;
            text-transform: uppercase;
        }
        .blocked-slot {
            background: #fee2e2;
            color: #991b1b;
            font-weight: bold;
            font-size: 8px;
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
        .duty-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 6px 10px;
            margin-top: 10px;
            font-size: 9px;
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
    @foreach($teachers as $teacher)
        @php
            $slots = $timetable[$teacher->id] ?? [];
            $inchargeOf = $teacher->inchargeSection;
            $primarySub = $teacher->primarySubject;
            $dutyList = $duties[$teacher->id] ?? [];
            $totalPeriodsTaught = 0;
            foreach($slots as $dSlots) {
                $totalPeriodsTaught += count($dSlots);
            }
        @endphp
        <div class="page">
            <table class="header-table">
                <tr>
                    <td style="width: 70%;">
                        <div class="inst-title">{{ $setting->name }}</div>
                        <div class="inst-sub">FACULTY WEEKLY TEACHING SCHEDULE & DUTY ROSTER | AY: {{ $setting->academic_year }}</div>
                    </td>
                    <td style="width: 30%; text-align: right;">
                        <span class="faculty-badge">{{ $teacher->code }} - {{ $teacher->name }}</span>
                    </td>
                </tr>
            </table>

            <div class="meta-bar">
                <span class="meta-item"><span class="meta-label">Specialist Subject:</span> {{ $primarySub ? $primarySub->name : 'N/A' }}</span>
                <span class="meta-item"><span class="meta-label">Teaching Workload:</span> <strong>{{ $totalPeriodsTaught }} / {{ $teacher->max_periods_per_week }} Periods</strong></span>
                <span class="meta-item"><span class="meta-label">Class Incharge Role:</span> {{ $inchargeOf->isNotEmpty() ? $inchargeOf->pluck('name')->implode(', ') : 'None' }}</span>
                <span class="meta-item"><span class="meta-label">Email:</span> {{ $teacher->email ?: 'N/A' }}</span>
                <span class="meta-item"><span class="meta-label">Phone:</span> {{ $teacher->phone ?: 'N/A' }}</span>
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
                                @elseif(!$teacher->isAvailable($day, $p))
                                    <td class="blocked-slot">UNAVAILABLE</td>
                                @else
                                    @php
                                        $slot = $slots[$day][$p] ?? null;
                                    @endphp
                                    <td>
                                        @if($slot)
                                            <div class="slot-card" style="background-color: {{ $slot->subject->color ?? '#059669' }};">
                                                <div style="font-weight: bold; font-size: 9.5px;">{{ $slot->section->name }}</div>
                                                <div style="font-size: 8.5px;">{{ $slot->subject->code }}</div>
                                                <div style="font-size: 8px; opacity: 0.9;">{{ $slot->room->name }}</div>
                                            </div>
                                        @else
                                            <span class="empty-slot">Free Period</span>
                                        @endif
                                    </td>
                                @endif
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(!empty($dutyList))
                <div class="duty-box">
                    <strong>🛡️ Campus Supervision & Duty Commitments:</strong>
                    @foreach($dutyList as $d)
                        <span style="display: inline-block; margin-right: 15px;">
                            • <strong>{{ $d->slot->day }}:</strong> {{ $d->slot->dutyType->name }} ({{ $d->slot->location }})
                        </span>
                    @endforeach
                </div>
            @endif

            <table class="footer-table">
                <tr>
                    <td style="width: 50%;">Generated by Institutional Timetable & Duty Solver (Laravel CSP Engine)</td>
                    <td style="width: 50%; text-align: right;">Faculty Signature: _______________________ &nbsp; Vice Principal: _______________________</td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>
