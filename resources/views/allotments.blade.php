@extends('layouts.app')
@section('title', 'Curriculum Period Allotment Center | Timetable Solver')

@section('content')
<div class="container-fluid">
    <!-- Header with Dual-Mode Toggle -->
    <div class="card card-flat p-3 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Dual-Mode Curriculum Period Allotment Center</h4>
                <p class="text-muted small mb-0">Configure curriculum subject quotas, assign specialist teachers, and set lab block sizes.</p>
            </div>

            <!-- Dual View Switcher Toggle -->
            <div class="btn-group shadow-sm" role="group">
                <a href="{{ route('allotments', ['view_mode' => 'section']) }}" class="btn {{ $viewMode === 'section' ? 'btn-primary' : 'btn-outline-secondary' }} fw-bold px-3">
                    <i class="fa-solid fa-school me-1"></i> [ 🏫 View by Class / Section ]
                </a>
                <a href="{{ route('allotments', ['view_mode' => 'teacher']) }}" class="btn {{ $viewMode === 'teacher' ? 'btn-success' : 'btn-outline-secondary' }} fw-bold px-3">
                    <i class="fa-solid fa-user-tie me-1"></i> [ 👨‍🏫 View by Teacher Workload ]
                </a>
            </div>
        </div>
    </div>

    <!-- Active Entity Selector & Live Capacity Meters -->
    @if($viewMode === 'section' && $activeSection)
        @php
            $allotted = $activeSection->total_allotted_periods;
            $free = max(0, $totalWeeklySlots - $allotted);
            $pct = min(100, round(($allotted / $totalWeeklySlots) * 100));
        @endphp
        <div class="card card-flat p-4 mb-4 border-start border-4 border-primary">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <label class="fw-bold text-dark">Active Class / Section:</label>
                    <select class="form-select form-select-sm fw-bold border-primary text-primary" style="width: 250px;" onchange="window.location.href='{{ route('allotments', ['view_mode' => 'section']) }}&id=' + this.value;">
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ $activeSection->id == $sec->id ? 'selected' : '' }}>
                                Section {{ $sec->name }} ({{ $sec->gradeClass->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-6 fw-bold text-dark">Allotted: <strong>{{ $allotted }} / {{ $totalWeeklySlots }} Periods</strong></span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">• {{ $free }} Free Study Periods</span>
                </div>
            </div>
            <!-- Live Progress Bar -->
            <div class="progress" style="height: 10px;">
                <div class="progress-bar {{ $allotted > $totalWeeklySlots ? 'bg-danger' : ($allotted == $totalWeeklySlots ? 'bg-warning' : 'bg-primary') }}"
                     role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    @elseif($viewMode === 'teacher' && $activeTeacher)
        @php
            $tLoad = $activeTeacher->lessonAllocations()->sum('periods_per_week');
            $maxW = $activeTeacher->max_periods_per_week ?: 24;
            $remLoad = max(0, $maxW - $tLoad);
            $pctT = min(100, round(($tLoad / $maxW) * 100));
        @endphp
        <div class="card card-flat p-4 mb-4 border-start border-4 border-success">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <label class="fw-bold text-dark">Active Faculty Member:</label>
                    <select class="form-select form-select-sm fw-bold border-success text-success" style="width: 300px;" onchange="window.location.href='{{ route('allotments', ['view_mode' => 'teacher']) }}&id=' + this.value;">
                        @foreach($teachers as $tch)
                            <option value="{{ $tch->id }}" {{ $activeTeacher->id == $tch->id ? 'selected' : '' }}>
                                {{ $tch->code }} — {{ $tch->name }} ({{ $tch->primarySubject?->code ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-6 fw-bold text-dark">Teacher Load: <strong>{{ $tLoad }} / {{ $maxW }} Periods</strong></span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">• {{ $remLoad }} Periods Remaining</span>
                </div>
            </div>
            <!-- Live Capacity Meter -->
            <div class="progress" style="height: 10px;">
                <div class="progress-bar {{ $tLoad > $maxW ? 'bg-danger' : ($tLoad == $maxW ? 'bg-warning' : 'bg-success') }}"
                     role="progressbar" style="width: {{ $pctT }}%;" aria-valuenow="{{ $pctT }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Add Allotment Form (Bidirectional Auto-Selection) -->
        <div class="col-lg-4">
            <div class="card card-flat p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-plus-circle text-primary me-2"></i> Add Lesson Allotment</h5>
                <form action="{{ route('allotments.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Target Class / Section</label>
                        <select name="section_id" id="formSectionId" class="form-select form-select-sm" required>
                            @foreach($sections as $sec)
                                <option value="{{ $sec->id }}" {{ $activeSection && $activeSection->id == $sec->id ? 'selected' : '' }}>
                                    Section {{ $sec->name }} ({{ $sec->gradeClass->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject Curriculum</label>
                        <select name="subject_id" id="formSubjectId" class="form-select form-select-sm" required onchange="handleSubjectChange(this.value)">
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" data-requires-lab="{{ $sub->requires_lab ? '1' : '0' }}" data-double="{{ $sub->allowed_double_period ? '1' : '0' }}">
                                    {{ $sub->code }} — {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Assigned Specialist Teacher</label>
                        <select name="teacher_id" id="formTeacherId" class="form-select form-select-sm" required onchange="handleTeacherChange(this.value)">
                            <option value="">-- Select Specialist Faculty --</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" data-primary-sub="{{ $t->primary_subject_id }}" {{ $activeTeacher && $activeTeacher->id == $t->id ? 'selected' : '' }}>
                                    {{ $t->code }} — {{ $t->name }} ({{ $t->primarySubject?->code ?? 'General' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Classroom / Facility</label>
                        <select name="room_id" id="formRoomId" class="form-select form-select-sm">
                            <option value="">-- Default Section Homeroom --</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->name }} ({{ $r->room_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Weekly Periods</label>
                            <input type="number" name="periods_per_week" class="form-control form-control-sm" value="4" min="1" max="10" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Block Size</label>
                            <select name="block_size" id="formBlockSize" class="form-select form-select-sm">
                                <option value="1">1 (Single Period)</option>
                                <option value="2">2 (Double Lab Block)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Sync Group ID (Macro-Task)</label>
                        <input type="text" name="sync_group_id" class="form-control form-control-sm" placeholder="e.g. SYNC_9_SCIENCE_LAB">
                        <small class="text-muted" style="font-size: 0.72rem;">Optional tag to align parallel practicals across sections.</small>
                    </div>

                    <div class="form-check form-switch mb-3 p-2 bg-light rounded border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="is_combined" id="formIsCombined" value="1">
                        <label class="form-check-label fw-bold small text-dark" for="formIsCombined">
                            👥 Combined Cohort Session (MPR / All Sections)
                        </label>
                        <div class="text-muted" style="font-size: 0.72rem;">
                            Applies to all sections of this Class (e.g. 8A, 8B, 8C) simultaneously with this teacher in MPR.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold py-2">
                        <i class="fa-solid fa-check me-1"></i> Save Allocation
                    </button>
                </form>
            </div>
        </div>

        <!-- Allotments List Table -->
        <div class="col-lg-8">
            <div class="card card-flat">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        @if($viewMode === 'section')
                            Curriculum Allotments for Section {{ $activeSection?->name }}
                        @else
                            Teaching Assignments for {{ $activeTeacher?->name }} ({{ $activeTeacher?->code }})
                        @endif
                    </h5>
                    <span class="badge bg-light text-dark border">{{ count($allocations) }} Allotments</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                @if($viewMode === 'teacher')
                                    <th>Section</th>
                                @endif
                                <th>Subject</th>
                                @if($viewMode === 'section')
                                    <th>Faculty Member</th>
                                @endif
                                <th>Facility</th>
                                <th class="text-center">Periods/Wk</th>
                                <th class="text-center">Block</th>
                                <th>Sync Macro</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allocations as $alloc)
                                <tr>
                                    @if($viewMode === 'teacher')
                                        <td><span class="badge bg-primary fs-6">{{ $alloc->section->name }}</span></td>
                                    @endif
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge" style="background-color: {{ $alloc->subject->color ?? '#3b82f6' }};">
                                                {{ $alloc->subject->code }}
                                            </span>
                                            <span class="small fw-semibold">{{ $alloc->subject->name }}</span>
                                        </div>
                                    </td>
                                    @if($viewMode === 'section')
                                        <td>
                                            <span class="small fw-bold text-dark">{{ $alloc->teacher->name }}</span>
                                            <span class="badge bg-light text-secondary border ms-1">{{ $alloc->teacher->code }}</span>
                                        </td>
                                    @endif
                                    <td>
                                        <span class="small text-muted">{{ $alloc->room ? $alloc->room->name : 'Home Room' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border fw-bold">{{ $alloc->periods_per_week }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($alloc->block_size > 1)
                                            <span class="badge bg-warning text-dark">Double ({{ $alloc->block_size }})</span>
                                        @else
                                            <span class="badge bg-light text-secondary border">Single (1)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($alloc->is_combined)
                                            <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-users me-1"></i> Combined Cohort</span>
                                            <span class="small d-block text-muted">{{ $alloc->room ? $alloc->room->name : 'MPR' }}</span>
                                        @elseif($alloc->sync_group_id)
                                            <span class="badge bg-info-subtle text-info border border-info-subtle small mono">{{ $alloc->sync_group_id }}</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" class="btn btn-outline-primary btn-sm p-1 px-2"
                                                    onclick="openEditAllotmentModal({{ $alloc->toJson() }})"
                                                    title="Edit Allotment">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <form action="{{ route('allotments.delete', $alloc->id) }}" method="POST" onsubmit="return confirm('Remove this allotment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm p-1 px-2" title="Delete Allotment">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No curriculum allocations found for this entity.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Allotment Modal -->
<div class="modal fade" id="editAllotmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Curriculum Allotment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editAllotmentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editAllocId">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Class Section</label>
                        <select name="section_id" id="editAllocSectionId" class="form-select form-select-sm" required>
                            @foreach($sections as $sec)
                                <option value="{{ $sec->id }}">Section {{ $sec->name }} ({{ $sec->gradeClass->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject Curriculum</label>
                        <select name="subject_id" id="editAllocSubjectId" class="form-select form-select-sm" required>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->code }} — {{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Assigned Specialist Teacher</label>
                        <select name="teacher_id" id="editAllocTeacherId" class="form-select form-select-sm" required>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">
                                    {{ $t->code }} — {{ $t->name }} ({{ $t->primarySubject?->code ?? 'General' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Classroom / Facility</label>
                        <select name="room_id" id="editAllocRoomId" class="form-select form-select-sm">
                            <option value="">-- Default Section Homeroom --</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->room_type }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Weekly Periods</label>
                            <input type="number" name="periods_per_week" id="editAllocPeriods" class="form-control form-control-sm" min="1" max="10" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Block Size</label>
                            <select name="block_size" id="editAllocBlockSize" class="form-select form-select-sm">
                                <option value="1">1 (Single Period)</option>
                                <option value="2">2 (Double Lab Block)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Sync Group ID (Macro-Task)</label>
                        <input type="text" name="sync_group_id" id="editAllocSyncGroup" class="form-control form-control-sm" placeholder="e.g. SYNC_LAB">
                    </div>

                    <div class="form-check form-switch mb-4 p-2 bg-light rounded border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="is_combined" id="editAllocIsCombined" value="1">
                        <label class="form-check-label fw-bold small text-dark" for="editAllocIsCombined">
                            👥 Combined Cohort Session (All Sections of Grade in MPR)
                        </label>
                        <div class="text-muted" style="font-size: 0.72rem;">
                            Cascades this allotment and schedules all sections of this Class simultaneously with this teacher in MPR.
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="fa-solid fa-check me-1"></i> Update Allotment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let editAllotmentModalInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('editAllotmentModal');
        if (modalEl) {
            editAllotmentModalInstance = new bootstrap.Modal(modalEl);
        }
    });

    function openEditAllotmentModal(alloc) {
        if (!editAllotmentModalInstance) {
            editAllotmentModalInstance = new bootstrap.Modal(document.getElementById('editAllotmentModal'));
        }

        document.getElementById('editAllocId').value = alloc.id;
        document.getElementById('editAllocSectionId').value = alloc.section_id;
        document.getElementById('editAllocSubjectId').value = alloc.subject_id;
        document.getElementById('editAllocTeacherId').value = alloc.teacher_id;
        document.getElementById('editAllocRoomId').value = alloc.room_id || '';
        document.getElementById('editAllocPeriods').value = alloc.periods_per_week;
        document.getElementById('editAllocBlockSize').value = alloc.block_size;
        document.getElementById('editAllocSyncGroup').value = alloc.sync_group_id || '';
        document.getElementById('editAllocIsCombined').checked = alloc.is_combined ? true : false;

        document.getElementById('editAllotmentForm').action = "{{ url('/allotments') }}/" + alloc.id + '/update';

        editAllotmentModalInstance.show();
    }
    // Bidirectional Auto-Selection Logic
    function handleSubjectChange(subId) {
        if (!subId) return;
        const teacherSelect = document.getElementById('formTeacherId');
        const options = Array.from(teacherSelect.options);

        // Sort teacher options: specialists in this subject first!
        options.sort((a, b) => {
            if (a.value === '') return -1;
            if (b.value === '') return 1;
            const aSub = a.getAttribute('data-primary-sub');
            const bSub = b.getAttribute('data-primary-sub');
            if (aSub === subId && bSub !== subId) return -1;
            if (bSub === subId && aSub !== subId) return 1;
            return 0;
        });

        // Re-append options
        teacherSelect.innerHTML = '';
        let firstMatch = null;
        options.forEach(opt => {
            if (opt.value && opt.getAttribute('data-primary-sub') === subId) {
                if (!opt.text.includes('⭐ Specialist')) {
                    opt.text = '⭐ Specialist: ' + opt.text.replace('⭐ Specialist: ', '');
                }
                if (!firstMatch) firstMatch = opt.value;
            } else if (opt.value) {
                opt.text = opt.text.replace('⭐ Specialist: ', '');
            }
            teacherSelect.appendChild(opt);
        });

        if (firstMatch) {
            teacherSelect.value = firstMatch;
        }
    }

    function handleTeacherChange(teacherId) {
        if (!teacherId) return;
        const teacherSelect = document.getElementById('formTeacherId');
        const selectedOpt = teacherSelect.options[teacherSelect.selectedIndex];
        const primarySubId = selectedOpt.getAttribute('data-primary-sub');

        if (primarySubId) {
            const subjectSelect = document.getElementById('formSubjectId');
            subjectSelect.value = primarySubId;
        }
    }
</script>
@endpush
