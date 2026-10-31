@extends('layouts.app')
@section('title', 'Institution & Calendar Settings | Timetable Solver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Institution & Academic Calendar Settings</h3>
            <p class="text-muted small mb-0">Configure daily period limits, Friday early dismissal cap, and recess break timings.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Settings Form -->
        <div class="col-lg-6">
            <div class="card card-flat p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-sliders text-primary me-2"></i> Configuration Parameters</h5>
                <form action="{{ route('institution.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Institution Name</label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ $setting->name }}" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Academic Year</label>
                            <input type="text" name="academic_year" class="form-control form-control-sm" value="{{ $setting->academic_year }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Academic Term</label>
                            <input type="text" name="term" class="form-control form-control-sm" value="{{ $setting->term }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Institutional Motto</label>
                        <input type="text" name="motto" class="form-control form-control-sm" value="{{ $setting->motto }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Active Working Days (6-Day Institution Calendar)</label>
                        <div class="d-flex flex-wrap gap-3 p-2 bg-light rounded border">
                            @php
                                $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $activeDays = $setting->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            @endphp
                            @foreach($allDays as $d)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="working_days[]" value="{{ $d }}" id="day_{{ $d }}"
                                           {{ in_array($d, $activeDays) ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-semibold" for="day_{{ $d }}">
                                        {{ $d }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-bold">Mon–Thu Periods</label>
                            <input type="number" name="periods_per_day" class="form-control form-control-sm" value="{{ $setting->periods_per_day }}" min="4" max="10" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold text-danger">Friday Periods (Less)</label>
                            <input type="number" name="friday_periods" class="form-control form-control-sm border-danger" value="{{ $setting->friday_periods }}" min="3" max="8" required>
                            <small class="text-danger" style="font-size: 0.7rem;">Jummah Dismissal</small>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold text-primary">Saturday Periods</label>
                            <input type="number" name="saturday_periods" class="form-control form-control-sm border-primary" value="{{ $setting->getPeriodsForDay('Saturday') ?: 6 }}" min="1" max="10">
                            <small class="text-primary" style="font-size: 0.7rem;">6th Working Day</small>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Recess Break After Period</label>
                            <input type="number" name="break_after_period" class="form-control form-control-sm" value="{{ $setting->break_after_period }}" min="2" max="6" required>
                            <small class="text-muted" style="font-size: 0.72rem;">Multi-period lab blocks cannot cross this boundary.</small>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Break Duration (Minutes)</label>
                            <input type="number" name="break_duration_mins" class="form-control form-control-sm" value="{{ $setting->break_duration_mins }}" min="10" max="60" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-bold">Period Duration (Mins)</label>
                            <input type="number" name="period_duration_mins" class="form-control form-control-sm" value="{{ $setting->period_duration_mins }}" min="20" max="90" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">School Start Hour</label>
                            <input type="number" name="start_hour" class="form-control form-control-sm" value="{{ $setting->start_hour }}" min="6" max="12" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Start Minute</label>
                            <input type="number" name="start_min" class="form-control form-control-sm" value="{{ $setting->start_min }}" min="0" max="59" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 py-2">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Calendar Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Default Schedule Timings Preview -->
        <div class="col-lg-6">
            <div class="card card-flat p-4 h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-clock text-primary me-2"></i> Bell Schedule & Timings Preview</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Period</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Duration</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $times = $setting->generateDefaultPeriodTimes();
                            @endphp
                            @foreach($times as $p => $t)
                                <tr>
                                    <td><strong class="text-primary">Period {{ $p }}</strong></td>
                                    <td><span class="mono">{{ $t['start_formatted'] }}</span></td>
                                    <td><span class="mono">{{ $t['end_formatted'] }}</span></td>
                                    <td>{{ $setting->period_duration_mins }} Mins</td>
                                    <td>
                                        @if($p === $setting->break_after_period)
                                            <span class="badge bg-warning text-dark">Recess Follows ({{ $setting->break_duration_mins }}m)</span>
                                        @elseif($p === $setting->friday_periods)
                                            <span class="badge bg-danger">Friday Dismissal</span>
                                        @else
                                            <span class="text-muted small">Standard Lesson</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
