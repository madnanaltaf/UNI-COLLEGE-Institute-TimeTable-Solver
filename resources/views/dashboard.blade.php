@extends('layouts.app')
@section('title', 'Dashboard & Diagnostics | Timetable Solver')

@section('content')
<div class="container-fluid">
    <!-- Header Title Banner -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-extrabold text-dark mb-1">Institutional Dashboard & Pre-Flight Diagnostics</h3>
            <p class="text-muted small mb-0">High-performance native Constraint Satisfaction Problem (CSP) solver engine for secondary schools & colleges.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('timetable') }}" class="btn btn-outline-primary btn-sm px-3 fw-semibold">
                <i class="fa-solid fa-calendar me-1"></i> Interactive Grid
            </a>
            <a href="{{ route('exports') }}" class="btn btn-primary btn-sm px-3 fw-semibold">
                <i class="fa-solid fa-file-pdf me-1"></i> Publication PDFs
            </a>
        </div>
    </div>

    <!-- Live Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-flat p-3 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Classes & Sections</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalSections }}</div>
                        <div class="small text-muted">Classes 6 to 12 (3 sections each)</div>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="fa-solid fa-school fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-flat p-3 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Specialist Faculty</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalTeachers }}</div>
                        <div class="small text-muted">1-Teacher = 1-Specialist Model</div>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle">
                        <i class="fa-solid fa-user-tie fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-flat p-3 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Curriculum Allotments</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalAllocations }}</div>
                        <div class="small text-muted">{{ $feasibility['stats']['total_lesson_periods'] }} Total Weekly Periods</div>
                    </div>
                    <div class="bg-warning-subtle text-warning p-3 rounded-circle">
                        <i class="fa-solid fa-book-open-reader fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-flat p-3 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Scheduled Slots</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalSlots }} / {{ $totalSections * 32 }}</div>
                        <div class="small text-muted">2 Free Study Periods / Section</div>
                    </div>
                    <div class="bg-info-subtle text-info p-3 rounded-circle">
                        <i class="fa-solid fa-calendar-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pre-Flight Feasibility Diagnostic Panel -->
    <div class="card card-flat mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-stethoscope text-primary fs-5"></i>
                <h5 class="fw-bold mb-0">Pre-Flight Diagnostic Feasibility Validator</h5>
            </div>
            <div>
                @if($feasibility['critical_count'] === 0)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-bold">
                        <i class="fa-solid fa-circle-check me-1"></i> Feasibility 100% Passed (Ready to Solve)
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fw-bold">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $feasibility['critical_count'] }} Blocking Issues Detected
                    </span>
                @endif
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="bg-light p-3 rounded-3 border">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-calculator me-1 text-primary"></i> School Calendar Quotas</h6>
                        <ul class="list-unstyled mb-0 small">
                            <li class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Standard Working Days:</span>
                                <strong>Monday — Friday (5 Days)</strong>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Mon–Thu Daily Periods:</span>
                                <strong>{{ $setting->periods_per_day }} Periods / Day</strong>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Friday Early Dismissal Cap:</span>
                                <strong class="text-danger">{{ $setting->friday_periods }} Periods (Jummah Prayer)</strong>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Total Weekly Slots:</span>
                                <strong>{{ $feasibility['stats']['total_weekly_slots'] }} Periods / Week</strong>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Recess Break Timing:</span>
                                <strong>After Period {{ $setting->break_after_period }} ({{ $setting->break_duration_mins }} Mins)</strong>
                            </li>
                            <li class="d-flex justify-content-between py-1">
                                <span class="text-muted">Period Duration:</span>
                                <strong>{{ $setting->period_duration_mins }} Minutes</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-8">
                    <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-list-check me-1 text-primary"></i> Real-Time Feasibility Diagnostics Report</h6>
                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 85px;">Severity</th>
                                    <th style="width: 100px;">Target</th>
                                    <th>Diagnostic Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($feasibility['issues'] as $issue)
                                    <tr>
                                        <td>
                                            @if($issue['type'] === 'CRITICAL')
                                                <span class="badge bg-danger text-white">CRITICAL</span>
                                            @elseif($issue['type'] === 'WARNING')
                                                <span class="badge bg-warning text-dark">ADVISORY</span>
                                            @else
                                                <span class="badge bg-info-subtle text-info border border-info-subtle">INFO</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $issue['entity'] }}:</strong> {{ $issue['name'] }}</td>
                                        <td class="small">{{ $issue['message'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No feasibility issues detected. System is ready to solve.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Cards -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card card-flat p-4 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary-subtle text-primary p-3 rounded-3">
                        <i class="fa-solid fa-calendar-days fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Interactive Timetable</h5>
                        <p class="text-muted small mb-0">Explore schedules across 4 view modes</p>
                    </div>
                </div>
                <p class="text-muted small">Switch between Section View, Faculty Schedule, Room Viewer, and Panoramic Master Institutional Matrix with Friday early dismissal badges.</p>
                <a href="{{ route('timetable') }}" class="btn btn-outline-primary btn-sm fw-semibold mt-auto">
                    Open Timetable Grid →
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-flat p-4 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-success-subtle text-success p-3 rounded-3">
                        <i class="fa-solid fa-sliders fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Period Allotment Center</h5>
                        <p class="text-muted small mb-0">Dual-mode curriculum assignments</p>
                    </div>
                </div>
                <p class="text-muted small">Dual view toggling between Section Curriculum Quotas and Teacher Workload Meters with automatic specialist recommendations.</p>
                <a href="{{ route('allotments') }}" class="btn btn-outline-success btn-sm fw-semibold mt-auto">
                    Manage Allotments →
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-flat p-4 h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-danger-subtle text-danger p-3 rounded-3">
                        <i class="fa-solid fa-file-pdf fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Export Center</h5>
                        <p class="text-muted small mb-0">Vector PDF Booklets & CSVs</p>
                    </div>
                </div>
                <p class="text-muted small">1-Click generation of high-resolution Class Timetable Booklets, Faculty Workload Schedules, Master Matrix, and Staff Duty Rosters.</p>
                <a href="{{ route('exports') }}" class="btn btn-outline-danger btn-sm fw-semibold mt-auto">
                    Download Publications →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
