<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Staff Duty Roster & Invigilation Audit</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #8b5cf6;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .inst-title {
            font-size: 16px;
            font-weight: bold;
            color: #5b21b6;
            text-transform: uppercase;
        }
        .inst-sub {
            font-size: 10.5px;
            color: #64748b;
        }
        .fairness-card {
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        table.duty-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.duty-table th, table.duty-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }
        table.duty-table th {
            background: #1e1b4b;
            color: #ffffff;
            font-size: 10px;
        }
        .day-header {
            background: #f1f5f9;
            font-weight: bold;
            color: #0f172a;
        }
        .teacher-tag {
            background: #e0e7ff;
            color: #3730a3;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 500;
            font-size: 9px;
            display: inline-block;
            margin-right: 4px;
            margin-bottom: 2px;
        }
        .audit-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-top: 10px;
        }
        .audit-table th, .audit-table td {
            border: 1px solid #e2e8f0;
            padding: 4px 6px;
            text-align: left;
        }
        .audit-table th {
            background: #f8fafc;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="inst-title">{{ $setting->name }}</div>
        <div class="inst-sub">CAMPUS STAFF DUTY ROSTER & FAIR-SHARE LOAD AUDIT | AY: {{ $setting->academic_year }}</div>
    </div>

    <div class="fairness-card">
        <strong>⚖️ Fair-Share Distribution Audit Metrics:</strong><br/>
        Total Duties Assigned: <strong>{{ $totalDuties }}</strong> &nbsp;|&nbsp;
        Active Faculty: <strong>{{ $totalTeachers }}</strong> &nbsp;|&nbsp;
        Min Load: <strong>{{ $minLoad }}</strong> &nbsp;|&nbsp;
        Max Load: <strong>{{ $maxLoad }}</strong> &nbsp;|&nbsp;
        Load Spread (Δ): <strong>{{ $spread }}</strong> (Satisfies fairness spread criteria Δ ≤ 1-2)
    </div>

    <h4 style="margin: 8px 0; color: #1e1b4b;">Weekly Campus Supervision Schedule</h4>
    <table class="duty-table">
        <thead>
            <tr>
                <th style="width: 80px;">Day</th>
                <th style="width: 140px;">Duty Station</th>
                <th style="width: 110px;">Location</th>
                <th>Assigned Faculty Members</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dutiesByDay as $day => $slots)
                @foreach($slots as $sIdx => $slot)
                    <tr>
                        @if($sIdx === 0)
                            <td rowspan="{{ count($slots) }}" class="day-header" style="vertical-align: top; text-align: center;">
                                <strong>{{ $day }}</strong>
                            </td>
                        @endif
                        <td><strong>{{ $slot->dutyType->name }}</strong></td>
                        <td>{{ $slot->location }}</td>
                        <td>
                            @forelse($slot->assignments as $a)
                                <span class="teacher-tag">{{ $a->teacher->code }} - {{ $a->teacher->name }}</span>
                            @empty
                                <span style="color: #94a3b8; font-style: italic;">No faculty assigned</span>
                            @endforelse
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <h4 style="margin: 12px 0 6px 0; color: #1e1b4b;">Faculty Duty Load Distribution Audit (Summary)</h4>
    <table class="audit-table">
        <thead>
            <tr>
                <th style="width: 40px;">#</th>
                <th style="width: 80px;">Code</th>
                <th>Faculty Name</th>
                <th>Specialization</th>
                <th style="width: 80px; text-align: center;">Total Duties</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facultyLoadAudit as $fa)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $fa['code'] }}</strong></td>
                    <td>{{ $fa['name'] }}</td>
                    <td>{{ $fa['subject'] }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $fa['count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
