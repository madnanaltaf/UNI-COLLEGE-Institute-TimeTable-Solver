<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Subject-Wise Curriculum Schedule</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #0284c7;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .inst-title {
            font-size: 15px;
            font-weight: bold;
            color: #0369a1;
            text-transform: uppercase;
        }
        .inst-sub {
            font-size: 10px;
            color: #64748b;
        }
        .sub-header {
            background: #f0f9ff;
            border-left: 4px solid #0284c7;
            padding: 6px 10px;
            margin-top: 15px;
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: bold;
            color: #0369a1;
        }
        table.sched-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.sched-table th, table.sched-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            text-align: left;
        }
        table.sched-table th {
            background: #0f172a;
            color: #ffffff;
            font-size: 9px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            color: #fff;
            font-weight: bold;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="inst-title">{{ $setting->name }}</div>
        <div class="inst-sub">MASTER SUBJECT-WISE CURRICULUM SCHEDULE REPORT | AY: {{ $setting->academic_year }}</div>
    </div>

    @foreach($subjects as $subject)
        @php
            $slots = $timetableBySubject[$subject->id] ?? [];
        @endphp
        <div class="sub-header">
            <span class="badge" style="background-color: {{ $subject->color }};">{{ $subject->code }}</span>
            {{ $subject->name }} ({{ $subject->subject_type }}) — Total Active Classes: {{ count($slots) }} Slots
        </div>

        @if(!empty($slots))
            <table class="sched-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Day</th>
                        <th style="width: 60px;">Period</th>
                        <th style="width: 70px;">Section</th>
                        <th>Teacher</th>
                        <th>Classroom / Facility</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($slots as $s)
                        <tr>
                            <td><strong>{{ $s->day }}</strong></td>
                            <td>Period {{ $s->period }}</td>
                            <td><strong>{{ $s->section->name }}</strong></td>
                            <td>{{ $s->teacher->name }} ({{ $s->teacher->code }})</td>
                            <td>{{ $s->room->name }} ({{ $s->room->room_type }})</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #94a3b8; font-style: italic; margin-left: 10px;">No scheduled slots found for this subject.</p>
        @endif
    @endforeach
</body>
</html>
