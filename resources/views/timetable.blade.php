@extends('layouts.app')
@section('title', 'Interactive Timetable Explorer | Timetable Solver')

@section('content')
<div class="container-fluid">
    <!-- View Mode Switcher Header -->
    <div class="card card-flat p-3 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <!-- 4 View Mode Buttons -->
            <div class="btn-group" role="group">
                <a href="{{ route('timetable', ['mode' => 'section']) }}" class="btn {{ $mode === 'section' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm fw-semibold">
                    <i class="fa-solid fa-graduation-cap me-1"></i> Section View
                </a>
                <a href="{{ route('timetable', ['mode' => 'teacher']) }}" class="btn {{ $mode === 'teacher' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm fw-semibold">
                    <i class="fa-solid fa-user-tie me-1"></i> Faculty View
                </a>
                <a href="{{ route('timetable', ['mode' => 'room']) }}" class="btn {{ $mode === 'room' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm fw-semibold">
                    <i class="fa-solid fa-door-open me-1"></i> Room View
                </a>
                <a href="{{ route('timetable', ['mode' => 'master']) }}" class="btn {{ $mode === 'master' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm fw-semibold">
                    <i class="fa-solid fa-table-cells me-1"></i> Master Matrix
                </a>
            </div>

            <!-- Entity Selector for Section / Teacher / Room -->
            @if($mode === 'section')
                <div class="d-flex align-items-center gap-2">
                    <label class="small fw-bold text-muted text-nowrap">Select Section:</label>
                    <select class="form-select form-select-sm fw-bold border-primary text-primary" onchange="window.location.href='{{ route('timetable', ['mode' => 'section']) }}&id=' + this.value;">
                        @foreach($sections as $s)
                            <option value="{{ $s->id }}" {{ $activeEntity && $activeEntity->id == $s->id ? 'selected' : '' }}>
                                Section {{ $s->name }} ({{ $s->gradeClass->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif($mode === 'teacher')
                <div class="d-flex align-items-center gap-2">
                    <label class="small fw-bold text-muted text-nowrap">Select Faculty Member:</label>
                    <select class="form-select form-select-sm fw-bold border-success text-success" onchange="window.location.href='{{ route('timetable', ['mode' => 'teacher']) }}&id=' + this.value;">
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ $activeEntity && $activeEntity->id == $t->id ? 'selected' : '' }}>
                                {{ $t->code }} — {{ $t->name }} ({{ $t->primarySubject?->code ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif($mode === 'room')
                <div class="d-flex align-items-center gap-2">
                    <label class="small fw-bold text-muted text-nowrap">Select Classroom / Lab:</label>
                    <select class="form-select form-select-sm fw-bold border-info text-info" onchange="window.location.href='{{ route('timetable', ['mode' => 'room']) }}&id=' + this.value;">
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}" {{ $activeEntity && $activeEntity->id == $r->id ? 'selected' : '' }}>
                                {{ $r->name }} ({{ $r->room_type }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="d-flex gap-2">
                <a href="{{ route('exports') }}" class="btn btn-outline-danger btn-sm fw-semibold">
                    <i class="fa-solid fa-file-pdf me-1"></i> PDF Export
                </a>
            </div>
        </div>
    </div>

    @if($mode === 'section' && $activeEntity)
        <!-- Prominent Class Incharge Banner -->
        <div class="alert alert-light border shadow-sm d-flex flex-wrap justify-content-between align-items-center mb-4 py-2 px-3 rounded-3">
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary fs-6 px-3 py-2">Class {{ $activeEntity->name }}</span>
                <div>
                    <span class="text-muted small">Designated Class Incharge / In-charge:</span>
                    <strong class="text-dark fs-6 ms-1">
                        @if($activeEntity->classTeacher)
                            👨‍🏫 {{ $activeEntity->classTeacher->name }} ({{ $activeEntity->classTeacher->code }})
                        @else
                            <span class="text-warning">Unassigned</span>
                        @endif
                    </strong>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 small text-muted">
                <span><i class="fa-solid fa-door-open me-1"></i> Base Room: <strong>{{ $activeEntity->homeRoom?->name ?? 'N/A' }}</strong></span>
                <span><i class="fa-solid fa-users me-1"></i> Strength: <strong>{{ $activeEntity->student_count }} Students</strong></span>
                <span><i class="fa-solid fa-clock me-1"></i> Weekly Load: <strong>{{ $activeEntity->total_allotted_periods }} / {{ $setting->getTotalWeeklySlots() }} Periods</strong></span>
            </div>
        </div>
    @elseif($mode === 'teacher' && $activeEntity)
        <div class="alert alert-light border shadow-sm d-flex flex-wrap justify-content-between align-items-center mb-4 py-2 px-3 rounded-3">
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-success fs-6 px-3 py-2">{{ $activeEntity->code }}</span>
                <span class="fs-6 fw-bold text-dark">{{ $activeEntity->name }}</span>
                <span class="badge bg-light text-secondary border">Specialist: {{ $activeEntity->primarySubject?->name }}</span>
            </div>
            <div class="d-flex align-items-center gap-3 small text-muted">
                <span>Incharge Role: <strong>{{ $activeEntity->inchargeSection->isNotEmpty() ? $activeEntity->inchargeSection->pluck('name')->implode(', ') : 'None' }}</strong></span>
                <span>Weekly Workload: <strong>{{ $activeEntity->timetableSlots()->count() }} / {{ $activeEntity->max_periods_per_week }} Periods</strong></span>
            </div>
        </div>
    @endif

    @if($mode === 'master')
        <!-- Panoramic Master Matrix View -->
        @foreach($workingDays as $day)
            @php
                $dayLimit = $setting->getPeriodsForDay($day);
            @endphp
            <div class="card card-flat mb-4">
                <div class="card-header bg-dark text-white py-2 px-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold">{{ strtoupper($day) }} INSTITUTIONAL MATRIX</span>
                    @if($day === 'Friday')
                        <span class="badge bg-danger">Early Dismissal after Period {{ $dayLimit }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $dayLimit }} Periods</span>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;">Section</th>
                                <th style="width: 140px;" class="text-start ps-3">Incharge</th>
                                @for($p = 1; $p <= $dayLimit; $p++)
                                    @if($p === ($setting->break_after_period + 1))
                                        <th style="width: 30px; background: #fef3c7; color: #92400e;" class="small fw-bold">BREAK</th>
                                    @endif
                                    <th>
                                        Period {{ $p }}<br/>
                                        <small class="text-muted fw-normal">{{ $periodTimes[$p]['start_formatted'] ?? '' }}</small>
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sections as $sec)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $sec->name }}</td>
                                    <td class="text-start ps-3 small text-muted">
                                        {{ $sec->classTeacher ? $sec->classTeacher->code . ' - ' . $sec->classTeacher->name : '—' }}
                                    </td>
                                    @for($p = 1; $p <= $dayLimit; $p++)
                                        @if($p === ($setting->break_after_period + 1))
                                            <td style="background: #fef3c7;"></td>
                                        @endif
                                        @php
                                            $slot = $slots[$day][$sec->id][$p] ?? null;
                                        @endphp
                                        <td class="p-1">
                                            @if($slot)
                                                <div class="p-1 rounded text-white small" style="background-color: {{ $slot->subject->color ?? '#3b82f6' }}; cursor: pointer;"
                                                     onclick="openEditModal({{ $slot->id }}, '{{ $day }}', {{ $p }}, {{ $slot->teacher_id }}, {{ $slot->room_id }}, '{{ $slot->subject->code }}', '{{ $slot->teacher->name }}', '{{ $slot->room->name }}', '{{ $sec->name }}', {{ $slot->is_combined ? 'true' : 'false' }}, '{{ $sec->name }}')">
                                                    <div class="fw-bold d-flex justify-content-between align-items-center">
                                                        <span>{{ $slot->subject->code }}</span>
                                                        @if($slot->is_combined)
                                                            <span class="badge bg-warning text-dark px-1" style="font-size: 0.6rem;">👥 Cohort</span>
                                                        @endif
                                                    </div>
                                                    <div style="font-size: 0.72rem; opacity: 0.9;">{{ $slot->teacher->code }} • {{ $slot->room->name }}</div>
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <!-- Grid View for Section, Teacher, or Room -->
        <div class="card card-flat">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center mb-0" style="table-layout: fixed;">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 110px;">Day / Time</th>
                            @for($p = 1; $p <= $maxPeriods; $p++)
                                @if($p === ($setting->break_after_period + 1))
                                    <th style="width: 45px; background: #d97706;" class="small fw-bold">RECESS</th>
                                @endif
                                <th>
                                    Period {{ $p }}<br/>
                                    <span style="font-size: 0.75rem; font-weight: normal; opacity: 0.85;">
                                        {{ $periodTimes[$p]['display'] ?? '' }}
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
                                <td class="fw-bold bg-light text-dark">{{ $day }}</td>
                                @for($p = 1; $p <= $maxPeriods; $p++)
                                    @if($p === ($setting->break_after_period + 1))
                                        <td style="background: #fef3c7; color: #92400e; font-weight: bold; font-size: 0.75rem;">
                                            BREAK
                                        </td>
                                    @endif

                                    @if($p > $dayLimit)
                                        <td class="bg-light text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                                — Early Dismissal —
                                            </span>
                                        </td>
                                    @else
                                        @php
                                            $slot = $slots[$day][$p] ?? null;
                                        @endphp
                                        <td class="p-2" style="height: 85px;">
                                            @if($slot)
                                                @php
                                                    $isCombined = $slot->is_combined || (!empty($slot->combined_sections) && count($slot->combined_sections) > 1);
                                                    $secDisplay = !empty($slot->combined_sections) ? implode(', ', $slot->combined_sections) : $slot->section->name;
                                                @endphp
                                                <div class="p-2 rounded text-white h-100 d-flex flex-column justify-content-between shadow-sm"
                                                     style="background-color: {{ $slot->subject->color ?? '#2563eb' }}; cursor: pointer; transition: transform 0.15s;"
                                                     onmouseover="this.style.transform='scale(1.02)'"
                                                     onmouseout="this.style.transform='scale(1)'"
                                                     onclick="openEditModal({{ $slot->id }}, '{{ $day }}', {{ $p }}, {{ $slot->teacher_id }}, {{ $slot->room_id }}, '{{ $slot->subject->code }}', '{{ $slot->teacher->name }}', '{{ $slot->room->name }}', '{{ $slot->section->name }}', {{ $isCombined ? 'true' : 'false' }}, '{{ $secDisplay }}')">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-bold fs-6">{{ $slot->subject->code }}</span>
                                                        @if($mode !== 'section')
                                                            @if(!empty($slot->combined_sections) && count($slot->combined_sections) > 1)
                                                                <span class="badge bg-warning text-dark fw-bold" title="Combined Cohort Session">
                                                                    👥 {{ implode(', ', $slot->combined_sections) }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-light text-dark fw-bold">{{ $slot->section->name }}</span>
                                                            @endif
                                                        @elseif($slot->is_combined)
                                                            <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.65rem;" title="Combined Cohort Lecture in {{ $slot->room->name }}">
                                                                👥 Combined ({{ $slot->room->name }})
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="small text-truncate" style="opacity: 0.95;">
                                                        @if($mode === 'section')
                                                            👨‍🏫 {{ $slot->teacher->name }} ({{ $slot->teacher->code }})
                                                        @else
                                                            {{ $slot->subject->name }}
                                                        @endif
                                                    </div>
                                                    <div style="font-size: 0.75rem; opacity: 0.9;">
                                                        <i class="fa-solid fa-door-open me-1"></i> {{ $slot->room->name }}
                                                        @if($isCombined)
                                                            <span class="badge bg-light text-dark ms-1" style="font-size: 0.65rem;">MPR Cohort</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-muted small h-100 d-flex align-items-center justify-content-center border border-dashed rounded bg-light">
                                                    <span>Free Study</span>
                                                </div>
                                            @endif
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

<!-- Interactive Edit Slot Dialog Modal -->
<div class="modal fade" id="editSlotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Timetable Slot
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info py-2 px-3 small mb-3">
                    Editing slot for Section: <strong id="modalSectionName"></strong> • Subject: <strong id="modalSubjectCode"></strong>
                </div>

                <div id="modalCombinedAlert" class="alert alert-warning py-2 px-3 small mb-3 d-none">
                    <i class="fa-solid fa-users me-1 text-dark"></i>
                    <strong class="text-dark">Combined Cohort Session (MPR / Mass Lecture):</strong><br/>
                    This lecture is shared simultaneously by all sections (<strong id="modalCombinedSections" class="text-primary"></strong>).
                    Moving this slot will <strong>automatically move all sections together</strong> to the new day and period!
                </div>

                <form id="editSlotForm">
                    <input type="hidden" id="editSlotId">

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Day of Week</label>
                            <select id="editDay" class="form-select form-select-sm">
                                @foreach($workingDays as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Period Slot</label>
                            <select id="editPeriod" class="form-select form-select-sm">
                                @for($p = 1; $p <= $maxPeriods; $p++)
                                    <option value="{{ $p }}">Period {{ $p }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Assigned Teacher</label>
                        <select id="editTeacherId" class="form-select form-select-sm">
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">
                                    {{ $t->code }} — {{ $t->name }} ({{ $t->primarySubject?->code ?? 'General' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Classroom / Laboratory</label>
                        <select id="editRoomId" class="form-select form-select-sm">
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->name }} ({{ $r->room_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="modalConflictAlert" class="alert alert-danger py-2 px-3 small d-none"></div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm fw-bold px-3" onclick="saveSlotChanges()">
                            <i class="fa-solid fa-check me-1"></i> Save Changes
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
    const editModal = new bootstrap.Modal(document.getElementById('editSlotModal'));

    function openEditModal(slotId, day, period, teacherId, roomId, subCode, teacherName, roomName, secName, isCombined = false, combinedSecs = '') {
        document.getElementById('editSlotId').value = slotId;
        document.getElementById('modalSectionName').innerText = secName;
        document.getElementById('modalSubjectCode').innerText = subCode;
        document.getElementById('editDay').value = day;
        document.getElementById('editPeriod').value = period;
        document.getElementById('editTeacherId').value = teacherId;
        document.getElementById('editRoomId').value = roomId;

        const combinedAlert = document.getElementById('modalCombinedAlert');
        if (isCombined) {
            document.getElementById('modalCombinedSections').innerText = combinedSecs || secName;
            combinedAlert.classList.remove('d-none');
        } else {
            combinedAlert.classList.add('d-none');
        }

        document.getElementById('modalConflictAlert').classList.add('d-none');
        document.getElementById('modalConflictAlert').innerText = '';

        editModal.show();
    }

    function saveSlotChanges() {
        const slotId = document.getElementById('editSlotId').value;
        const day = document.getElementById('editDay').value;
        const period = document.getElementById('editPeriod').value;
        const teacherId = document.getElementById('editTeacherId').value;
        const roomId = document.getElementById('editRoomId').value;
        const conflictAlert = document.getElementById('modalConflictAlert');

        fetch("{{ route('slot.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                slot_id: slotId,
                day: day,
                period: period,
                teacher_id: teacherId,
                room_id: roomId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                editModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Slot Updated',
                    text: data.message,
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                conflictAlert.innerText = data.message;
                conflictAlert.classList.remove('d-none');
            }
        })
        .catch(err => {
            conflictAlert.innerText = 'Server error occurred while validating conflict.';
            conflictAlert.classList.remove('d-none');
        });
    }
</script>
@endpush
