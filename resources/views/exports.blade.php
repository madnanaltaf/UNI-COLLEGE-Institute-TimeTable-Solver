@extends('layouts.app')
@section('title', 'Publication-Ready Reporting & Export Center | Timetable Solver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Publication-Ready Reporting & Export Center</h3>
            <p class="text-muted small mb-0">Generate crisp vector PDF booklets with custom color palettes, running headers, and CSV spreadsheets for school ERP ingestion.</p>
        </div>
    </div>

    @if(!$hasTimetable)
        <div class="alert alert-warning d-flex align-items-center mb-4 p-3 rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
            <div>
                <strong>No Active Timetable Solved:</strong> Please generate a conflict-free timetable first before downloading publication booklets.
                <button class="btn btn-sm btn-dark ms-3 btn-trigger-solve"><i class="fa-solid fa-bolt me-1"></i> Generate Timetable Now</button>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- 1. Class & Section Timetable Booklet -->
        <div class="col-lg-6">
            <div class="card card-flat p-4 h-100 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary p-3 rounded-3">
                            <i class="fa-solid fa-graduation-cap fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Class & Section Timetable Booklet</h5>
                            <span class="badge bg-light text-secondary border mt-1">Multi-Page Section Booklet (A4 Landscape)</span>
                        </div>
                    </div>
                </div>
                <p class="text-muted small">
                    Complete booklet covering all 21 Sections (Classes 6A to 12C). Features Institution Crest, Designated Class Incharge, Base Homeroom, Student Strength, Subject Color Chips, and Friday early dismissal markers.
                </p>
                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('exports.pdf', 'sections') }}" target="_blank" class="btn btn-primary btn-sm fw-bold px-3">
                        <i class="fa-solid fa-file-pdf me-1"></i> Download PDF Booklet
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Faculty & Teachers Timetable Booklet -->
        <div class="col-lg-6">
            <div class="card card-flat p-4 h-100 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success-subtle text-success p-3 rounded-3">
                            <i class="fa-solid fa-user-tie fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Faculty & Teachers Timetable Booklet</h5>
                            <span class="badge bg-light text-secondary border mt-1">Individual Faculty Schedules (A4 Landscape)</span>
                        </div>
                    </div>
                </div>
                <p class="text-muted small">
                    Dedicated weekly timetable for each of the 43 specialist faculty members. Displays Subject Specialization, weekly workload meters, assigned Class Incharge roles, and campus supervision duty commitments.
                </p>
                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('exports.pdf', 'faculty') }}" target="_blank" class="btn btn-success btn-sm fw-bold px-3">
                        <i class="fa-solid fa-file-pdf me-1"></i> Download Faculty Booklet
                    </a>
                </div>
            </div>
        </div>

        <!-- 3. Master Institutional Matrix -->
        <div class="col-lg-6">
            <div class="card card-flat p-4 h-100 border-start border-4 border-dark">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-dark text-white p-3 rounded-3">
                            <i class="fa-solid fa-table-cells fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Panoramic Master Institutional Matrix</h5>
                            <span class="badge bg-light text-secondary border mt-1">High-Resolution Matrix (A3 Landscape)</span>
                        </div>
                    </div>
                </div>
                <p class="text-muted small">
                    Comprehensive panoramic schedule displaying all 21 sections across each day of the week. Dynamically sizes daily columns (e.g., Friday master table renders cleanly with 4 periods and Jummah Prayer early dismissal banner).
                </p>
                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('exports.pdf', 'master') }}" target="_blank" class="btn btn-dark btn-sm fw-bold px-3">
                        <i class="fa-solid fa-file-pdf me-1"></i> Download Master Matrix
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Staff Duty Roster & Invigilation Audit -->
        <div class="col-lg-6">
            <div class="card card-flat p-4 h-100 border-start border-4 border-purple" style="border-left-color: #8b5cf6 !important;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-3 text-white" style="background: #8b5cf6;">
                            <i class="fa-solid fa-shield-halved fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Staff Duty Roster & Invigilation Audit</h5>
                            <span class="badge bg-light text-secondary border mt-1">Campus Supervision Report (A4 Portrait)</span>
                        </div>
                    </div>
                </div>
                <p class="text-muted small">
                    Weekly campus supervision schedule for Morning Assembly, Main Gate, and Recess corridor monitoring with complete faculty fairness distribution audit metrics ($\Delta \le 1–2$).
                </p>
                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('exports.pdf', 'duty') }}" target="_blank" class="btn btn-sm fw-bold px-3 text-white" style="background: #8b5cf6;">
                        <i class="fa-solid fa-file-pdf me-1"></i> Download Duty Roster
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. Subject-Wise Curriculum Schedules -->
        <div class="col-lg-6">
            <div class="card card-flat p-4 h-100 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info-subtle text-info p-3 rounded-3">
                            <i class="fa-solid fa-layer-group fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Subject-Wise Curriculum Schedule</h5>
                            <span class="badge bg-light text-secondary border mt-1">Chronological Subject Master (A4 Portrait)</span>
                        </div>
                    </div>
                </div>
                <p class="text-muted small">
                    Master timetable organized chronologically by academic subject discipline (English, Urdu, Mathematics, Physics, Chemistry, Biology, IT, etc.) across all class levels.
                </p>
                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('exports.pdf', 'subjects') }}" target="_blank" class="btn btn-info text-white btn-sm fw-bold px-3">
                        <i class="fa-solid fa-file-pdf me-1"></i> Download Subject Schedules
                    </a>
                </div>
            </div>
        </div>

        <!-- 6. ERP & SIS Data Interoperability (CSV & JSON) -->
        <div class="col-lg-6">
            <div class="card card-flat p-4 h-100 border-start border-4 border-secondary">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-secondary-subtle text-secondary p-3 rounded-3">
                            <i class="fa-solid fa-file-csv fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">School ERP / SIS Spreadsheet Exports</h5>
                            <span class="badge bg-light text-secondary border mt-1">CSV Spreadsheets & JSON Project File</span>
                        </div>
                    </div>
                </div>
                <p class="text-muted small">
                    Standard CSV spreadsheet exports ready for direct ingestion into external Student Information Systems (SIS) and Human-readable JSON project persistence.
                </p>
                <div class="d-flex flex-wrap gap-2 mt-auto">
                    <a href="{{ route('exports.csv', 'master') }}" class="btn btn-outline-secondary btn-sm fw-bold">
                        <i class="fa-solid fa-file-csv me-1"></i> Master Timetable (CSV)
                    </a>
                    <a href="{{ route('exports.csv', 'duty') }}" class="btn btn-outline-secondary btn-sm fw-bold">
                        <i class="fa-solid fa-file-csv me-1"></i> Staff Duty Roster (CSV)
                    </a>
                    <a href="{{ route('exports.json') }}" class="btn btn-outline-dark btn-sm fw-bold">
                        <i class="fa-solid fa-code me-1"></i> Project Backup (JSON)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
