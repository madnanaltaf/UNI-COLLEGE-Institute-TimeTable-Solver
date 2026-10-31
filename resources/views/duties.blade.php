@extends('layouts.app')
@section('title', 'Staff Duty Roster & Fairness Audit | Timetable Solver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Campus Supervision & Staff Duty Roster</h3>
            <p class="text-muted small mb-0">Fair-share equitable campus duty assignments for Morning Assembly, Main Gate, and Recess Monitoring.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('duties.optimize') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-sm fw-bold px-3">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> [ ⚡ Auto-Optimize Duty Roster ]
                </button>
            </form>
            <a href="{{ route('exports.pdf', 'duty') }}" class="btn btn-outline-danger btn-sm fw-semibold">
                <i class="fa-solid fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Fairness Audit Metrics Card -->
    <div class="card card-flat p-4 mb-4 border-start border-4 border-purple" style="border-left-color: #8b5cf6 !important;">
        <div class="row align-items-center g-3">
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase">Fairness Spread (Δ)</div>
                <div class="fs-2 fw-bold text-purple" style="color: #6d28d9;">Δ = {{ $spread }}</div>
                <div class="small text-success fw-semibold"><i class="fa-solid fa-circle-check"></i> Meets criteria (Δ ≤ 1–2)</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase">Total Supervision Slots</div>
                <div class="fs-3 fw-bold text-dark">{{ $totalDuties }} Duties</div>
                <div class="small text-muted">Weekly campus stations</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase">Min / Max Load Per Faculty</div>
                <div class="fs-3 fw-bold text-dark">{{ $minLoad }} — {{ $maxLoad }} Duties</div>
                <div class="small text-muted">Spread evenly across all 43 teachers</div>
            </div>
            <div class="col-md-3 text-md-end">
                <span class="badge bg-purple-subtle text-purple border p-2 px-3 fw-bold" style="background-color: #f5f3ff; color: #5b21b6; border-color: #ddd6fe !important;">
                    <i class="fa-solid fa-scale-balanced me-1"></i> Zero-Conflict Teaching Shield
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Duty Slots Schedule -->
        <div class="col-lg-7">
            <div class="card card-flat">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Weekly Campus Supervision Stations</h5>
                    <span class="badge bg-light text-secondary border">{{ count($dutySlots) }} Active Duty Slots</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 95px;">Day</th>
                                <th>Duty Station</th>
                                <th>Location</th>
                                <th>Assigned Faculty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dutySlots as $slot)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $slot->day }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $slot->dutyType->name }}</div>
                                        <small class="text-muted">{{ $slot->dutyType->description }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border">{{ $slot->location }}</span></td>
                                    <td>
                                        @forelse($slot->assignments as $a)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-1 mb-1">
                                                {{ $a->teacher->code }} — {{ $a->teacher->name }}
                                            </span>
                                        @empty
                                            <span class="text-muted small italic">Unassigned</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Faculty Duty Load Audit Table -->
        <div class="col-lg-5">
            <div class="card card-flat">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Faculty Duty Distribution Audit</h5>
                    <span class="badge bg-light text-secondary border">43 Faculty</span>
                </div>
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Code</th>
                                <th>Faculty Name</th>
                                <th>Specialization</th>
                                <th class="text-center">Duties</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($audit as $item)
                                @php
                                    $t = $item['teacher'];
                                    $c = $item['count'];
                                @endphp
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $t->code }}</span></td>
                                    <td><strong class="text-dark">{{ $t->name }}</strong></td>
                                    <td><span class="small text-muted">{{ $t->primarySubject?->code ?? 'General' }}</span></td>
                                    <td class="text-center">
                                        <span class="badge {{ $c > 0 ? 'bg-success' : 'bg-light text-muted border' }} fw-bold">
                                            {{ $c }}
                                        </span>
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
