@extends('layouts.app')
@section('title', 'Faculty Directory & Availability Matrix | Timetable Solver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Faculty & Teachers Directory</h3>
            <p class="text-muted small mb-0">Manage 1-Teacher = 1-Specialist faculty assignments and interactive time-off availability matrices.</p>
        </div>
        <button class="btn btn-primary btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
            <i class="fa-solid fa-user-plus me-1"></i> Add Faculty Member
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center py-2 px-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-check fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-4" role="alert">
            <ul class="mb-0 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Faculty Directory List -->
        <div class="col-lg-5">
            <div class="card card-flat">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Specialist Faculty ({{ count($teachers) }})</h5>
                    <span class="badge bg-light text-secondary border">Select to View Matrix</span>
                </div>
                <div class="list-group list-group-flush" style="max-height: 680px; overflow-y: auto;">
                    @foreach($teachers as $t)
                        @php
                            $isSelected = $selectedTeacher && $selectedTeacher->id === $t->id;
                            $tLoad = $t->lessonAllocations->sum('periods_per_week');
                        @endphp
                        <a href="{{ route('faculty', ['teacher_id' => $t->id]) }}"
                           class="list-group-item list-group-item-action p-3 {{ $isSelected ? 'bg-primary-subtle border-start border-4 border-primary' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold text-dark fs-6">{{ $t->name }}</div>
                                    <div class="small text-muted mt-1">
                                        <span class="badge bg-secondary-subtle text-secondary border me-1">{{ $t->code }}</span>
                                        <span class="badge" style="background-color: {{ $t->primarySubject?->color ?? '#64748b' }}; color: #fff;">
                                            {{ $t->primarySubject?->name ?? 'General' }}
                                        </span>
                                    </div>
                                    @if($t->inchargeSection->isNotEmpty())
                                        <div class="small text-primary mt-1">
                                            👨‍🏫 Class Incharge: <strong>{{ $t->inchargeSection->pluck('name')->implode(', ') }}</strong>
                                        </div>
                                    @endif
                                    @if($t->email || $t->phone)
                                        <div class="small text-muted mt-1" style="font-size: 0.78rem;">
                                            @if($t->email) <span><i class="fa-regular fa-envelope me-1"></i>{{ $t->email }}</span> @endif
                                            @if($t->phone) <span class="ms-2"><i class="fa-solid fa-phone me-1"></i>{{ $t->phone }}</span> @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $tLoad > $t->max_periods_per_week ? 'bg-danger' : 'bg-light text-dark border' }} fw-bold">
                                        {{ $tLoad }} / {{ $t->max_periods_per_week }} Periods
                                    </span>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fw-semibold"
                                                onclick="event.preventDefault(); event.stopPropagation(); openEditTeacherModal({{ $t->toJson() }})"
                                                title="Edit Faculty Member">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Interactive Availability Matrix -->
        <div class="col-lg-7">
            @if($selectedTeacher)
                <div class="card card-flat p-4 mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <span class="badge bg-primary fs-6 px-3 py-1">{{ $selectedTeacher->code }}</span>
                            <h4 class="fw-bold text-dark d-inline ms-2">{{ $selectedTeacher->name }}</h4>
                            <div class="text-muted small mt-1">
                                Specialist: <strong>{{ $selectedTeacher->primarySubject?->name }}</strong> • 
                                Max: <strong>{{ $selectedTeacher->max_periods_per_week }} Periods/Week</strong> ({{ $selectedTeacher->max_periods_per_day }}/Day)
                                @if($selectedTeacher->email) • <span class="text-muted">{{ $selectedTeacher->email }}</span> @endif
                            </div>
                        </div>

                        <!-- Action Buttons: Edit Teacher & 1-Click Presets -->
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm"
                                    onclick="openEditTeacherModal({{ $selectedTeacher->toJson() }})">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Teacher
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm fw-bold px-2"
                                    onclick="confirmDeleteTeacher({{ $selectedTeacher->id }}, '{{ addslashes($selectedTeacher->name) }}')">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>

                    <!-- 1-Click Fast Time-Off Preset Buttons Ribbon -->
                    <div class="bg-light p-2 rounded border mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <span class="small fw-bold text-muted"><i class="fa-solid fa-wand-magic-sparkles me-1 text-primary"></i> Quick Presets:</span>
                        <div class="d-flex flex-wrap gap-1">
                            <form action="{{ route('faculty.preset') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">
                                <input type="hidden" name="preset" value="all_free">
                                <button type="submit" class="btn btn-outline-success btn-sm fw-semibold">
                                    [ ✓ All Free ]
                                </button>
                            </form>
                            <form action="{{ route('faculty.preset') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">
                                <input type="hidden" name="preset" value="friday_dismissal">
                                <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                                    [ 🚫 Friday Dismissal ]
                                </button>
                            </form>
                            <form action="{{ route('faculty.preset') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">
                                <input type="hidden" name="preset" value="block_p1">
                                <button type="submit" class="btn btn-outline-warning btn-sm fw-semibold">
                                    [ 🚫 Block Period 1 ]
                                </button>
                            </form>
                            <form action="{{ route('faculty.preset') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">
                                <input type="hidden" name="preset" value="block_late">
                                <button type="submit" class="btn btn-outline-dark btn-sm fw-semibold">
                                    [ 🚫 Block Late Period ]
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="alert alert-light border small text-muted py-2 px-3 mb-3">
                        <i class="fa-solid fa-info-circle text-primary me-1"></i>
                        Click any slot in the grid below to toggle between <strong>Free</strong> and <strong>Blocked</strong> (unavailable for scheduling). Changes save in real-time.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 100px;">Day</th>
                                    @for($p = 1; $p <= $maxPeriods; $p++)
                                        <th>Period {{ $p }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workingDays as $day)
                                    @php
                                        $dayLimit = $setting->getPeriodsForDay($day);
                                    @endphp
                                    <tr>
                                        <td class="fw-bold bg-light">{{ $day }}</td>
                                        @for($p = 1; $p <= $maxPeriods; $p++)
                                            @if($p > $dayLimit)
                                                <td class="bg-light text-muted small">— Dismissal —</td>
                                            @else
                                                @php
                                                    $isAvail = $selectedTeacher->isAvailable($day, $p);
                                                @endphp
                                                <td class="p-1">
                                                    <button type="button"
                                                            class="btn btn-sm w-100 py-2 fw-bold {{ $isAvail ? 'btn-outline-success' : 'btn-danger' }}"
                                                            onclick="toggleSlotAvailability({{ $selectedTeacher->id }}, '{{ $day }}', {{ $p }}, this)">
                                                        @if($isAvail)
                                                            <i class="fa-solid fa-check"></i> Free
                                                        @else
                                                            <i class="fa-solid fa-ban"></i> Blocked
                                                        @endif
                                                    </button>
                                                </td>
                                            @endif
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-user-plus me-2"></i> Add Specialist Faculty Member
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('faculty.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name (with Title)</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Dr. Albert Einstein" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Teacher Code / Initials</label>
                            <input type="text" name="code" class="form-control form-control-sm" placeholder="Auto-generated if blank (e.g. AE)">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Primary Specialist Subject</label>
                            <select name="primary_subject_id" class="form-select form-select-sm" required>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->code }} — {{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="faculty@school.edu">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-sm" placeholder="+92-300-XXXXXXX">
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Max Periods / Week</label>
                            <input type="number" name="max_periods_per_week" class="form-control form-control-sm" value="24" min="1" max="40" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Max Periods / Day</label>
                            <input type="number" name="max_periods_per_day" class="form-control form-control-sm" value="5" min="1" max="10" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="fa-solid fa-check me-1"></i> Add Faculty Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Teacher Modal -->
<div class="modal fade" id="editTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Faculty Member
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editTeacherForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editTeacherId" name="teacher_id">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name (with Title)</label>
                        <input type="text" id="editName" name="name" class="form-control form-control-sm" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Teacher Code / Initials</label>
                            <input type="text" id="editCode" name="code" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-primary">Specialist Single Subject</label>
                            <select id="editPrimarySubjectId" name="primary_subject_id" class="form-select form-select-sm fw-bold border-primary" required>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->code }} — {{ $sub->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-muted" style="font-size: 0.72rem;">1-Teacher = 1-Specialist model</div>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" id="editEmail" name="email" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Phone Number</label>
                            <input type="text" id="editPhone" name="phone" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Max Periods / Week</label>
                            <input type="number" id="editMaxPeriodsWeek" name="max_periods_per_week" class="form-control form-control-sm" min="1" max="40" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Max Periods / Day</label>
                            <input type="number" id="editMaxPeriodsDay" name="max_periods_per_day" class="form-control form-control-sm" min="1" max="10" required>
                        </div>
                    </div>

                    <div class="alert alert-light border small text-muted py-2 px-3 mb-4">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>
                        Selecting a new specialist subject will automatically reassign this teacher's class allotments and timetable slots to the new subject.
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="triggerDeleteFromModal()">
                            <i class="fa-solid fa-trash me-1"></i> Delete Faculty
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                                <i class="fa-solid fa-check me-1"></i> Update Faculty Details
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteTeacherForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    let editTeacherModalInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('editTeacherModal');
        if (modalEl) {
            editTeacherModalInstance = new bootstrap.Modal(modalEl);
        }
    });

    function openEditTeacherModal(teacher) {
        if (!editTeacherModalInstance) {
            editTeacherModalInstance = new bootstrap.Modal(document.getElementById('editTeacherModal'));
        }

        document.getElementById('editTeacherId').value = teacher.id;
        document.getElementById('editName').value = teacher.name;
        document.getElementById('editCode').value = teacher.code;
        document.getElementById('editPrimarySubjectId').value = teacher.primary_subject_id;
        document.getElementById('editEmail').value = teacher.email || '';
        document.getElementById('editPhone').value = teacher.phone || '';
        document.getElementById('editMaxPeriodsWeek').value = teacher.max_periods_per_week || 24;
        document.getElementById('editMaxPeriodsDay').value = teacher.max_periods_per_day || 5;

        // Set action
        const baseUrl = "{{ url('/faculty') }}";
        document.getElementById('editTeacherForm').action = baseUrl + '/' + teacher.id + '/update';

        editTeacherModalInstance.show();
    }

    function confirmDeleteTeacher(teacherId, teacherName) {
        if (confirm(`Are you sure you want to delete faculty member "${teacherName}"? This will also remove their timetable slots and allotments.`)) {
            const deleteForm = document.getElementById('deleteTeacherForm');
            deleteForm.action = "{{ url('/faculty') }}/" + teacherId;
            deleteForm.submit();
        }
    }

    function triggerDeleteFromModal() {
        const teacherId = document.getElementById('editTeacherId').value;
        const teacherName = document.getElementById('editName').value;
        confirmDeleteTeacher(teacherId, teacherName);
    }

    function toggleSlotAvailability(teacherId, day, period, btn) {
        fetch("{{ route('faculty.toggle') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                teacher_id: teacherId,
                day: day,
                period: period
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.status === 'blocked') {
                    btn.className = 'btn btn-sm w-100 py-2 fw-bold btn-danger';
                    btn.innerHTML = '<i class="fa-solid fa-ban"></i> Blocked';
                } else {
                    btn.className = 'btn btn-sm w-100 py-2 fw-bold btn-outline-success';
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Free';
                }
            }
        });
    }
</script>
@endpush
