@extends('layouts.app')
@section('title', 'Subjects & Curriculum Directory | Timetable Solver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Curriculum Subjects Directory</h3>
            <p class="text-muted small mb-0">Manage subject disciplines, laboratory requirements, color tags, and cognitive fatigue ratings.</p>
        </div>
        <button class="btn btn-primary btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="fa-solid fa-plus me-1"></i> Add Subject
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center py-2 px-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-check fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-flat">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 70px;">Color</th>
                        <th style="width: 80px;">Code</th>
                        <th>Subject Name</th>
                        <th>Subject Type</th>
                        <th>Facility Requirement</th>
                        <th>Cognitive Fatigue</th>
                        <th>Double Block</th>
                        <th class="text-center">Allotments</th>
                        <th class="text-end" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $sub)
                        <tr>
                            <td>
                                <div style="width: 28px; height: 28px; border-radius: 6px; background-color: {{ $sub->color }}; box-shadow: 0 2px 4px rgba(0,0,0,0.15);"></div>
                            </td>
                            <td><span class="badge bg-secondary fs-6">{{ $sub->code }}</span></td>
                            <td><strong class="text-dark">{{ $sub->name }}</strong></td>
                            <td><span class="badge bg-light text-dark border">{{ $sub->subject_type }}</span></td>
                            <td>
                                @if($sub->requires_lab)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        <i class="fa-solid fa-flask me-1"></i> {{ $sub->default_room_type }}
                                    </span>
                                @else
                                    <span class="text-muted small">{{ $sub->default_room_type }}</span>
                                @endif
                            </td>
                            <td>
                                @if($sub->is_difficult)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        <i class="fa-solid fa-brain me-1"></i> High Fatigue
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Standard</span>
                                @endif
                            </td>
                            <td>
                                @if($sub->allowed_double_period)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">2-Period Block</span>
                                @else
                                    <span class="text-muted small">Single Only</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $sub->lesson_allocations_count }}</span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fw-semibold"
                                        onclick="openEditSubjectModal({{ $sub->toJson() }})">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2 fw-semibold ms-1"
                                        onclick="confirmDeleteSubject({{ $sub->id }}, '{{ addslashes($sub->name) }}')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-book me-2"></i> Add Curriculum Subject
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('subjects.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject Name</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Computer Science" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Subject Code</label>
                            <input type="text" name="code" class="form-control form-control-sm" placeholder="e.g. CS" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Subject Type</label>
                            <select name="subject_type" class="form-select form-select-sm">
                                <option value="Theory">Theory</option>
                                <option value="Practical Lab">Practical Lab</option>
                                <option value="Elective">Elective</option>
                                <option value="P.E">Physical Education</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Grid Color Chip</label>
                            <input type="color" name="color" class="form-control form-control-color w-100" value="#2563eb">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Default Facility Type</label>
                            <input type="text" name="default_room_type" class="form-control form-control-sm" value="General Classroom">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="requires_lab" value="1" id="requiresLabCheck">
                            <label class="form-check-label small fw-semibold" for="requiresLabCheck">Requires Dedicated Specialized Laboratory</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_difficult" value="1" id="difficultCheck">
                            <label class="form-check-label small fw-semibold" for="difficultCheck">High Cognitive Fatigue (Discourage placement in afternoon)</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="allowed_double_period" value="1" id="doubleCheck">
                            <label class="form-check-label small fw-semibold" for="doubleCheck">Allow Multi-Period Consecutive Block (2-period lab)</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="fa-solid fa-check me-1"></i> Create Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Curriculum Subject
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editSubjectForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editSubjectId">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject Name</label>
                        <input type="text" id="editSubName" name="name" class="form-control form-control-sm" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Subject Code</label>
                            <input type="text" id="editSubCode" name="code" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Subject Type</label>
                            <select id="editSubType" name="subject_type" class="form-select form-select-sm">
                                <option value="Theory">Theory</option>
                                <option value="Practical Lab">Practical Lab</option>
                                <option value="Elective">Elective</option>
                                <option value="P.E">Physical Education</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Grid Color Chip</label>
                            <input type="color" id="editSubColor" name="color" class="form-control form-control-color w-100">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Default Facility Type</label>
                            <input type="text" id="editSubRoomType" name="default_room_type" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="requires_lab" value="1" id="editSubRequiresLab">
                            <label class="form-check-label small fw-semibold" for="editSubRequiresLab">Requires Dedicated Specialized Laboratory</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_difficult" value="1" id="editSubDifficult">
                            <label class="form-check-label small fw-semibold" for="editSubDifficult">High Cognitive Fatigue (Discourage placement in afternoon)</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="allowed_double_period" value="1" id="editSubDouble">
                            <label class="form-check-label small fw-semibold" for="editSubDouble">Allow Multi-Period Consecutive Block (2-period lab)</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="fa-solid fa-check me-1"></i> Update Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteSubjectForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    let editSubjectModalInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('editSubjectModal');
        if (modalEl) {
            editSubjectModalInstance = new bootstrap.Modal(modalEl);
        }
    });

    function openEditSubjectModal(sub) {
        if (!editSubjectModalInstance) {
            editSubjectModalInstance = new bootstrap.Modal(document.getElementById('editSubjectModal'));
        }

        document.getElementById('editSubjectId').value = sub.id;
        document.getElementById('editSubName').value = sub.name;
        document.getElementById('editSubCode').value = sub.code;
        document.getElementById('editSubType').value = sub.subject_type;
        document.getElementById('editSubColor').value = sub.color;
        document.getElementById('editSubRoomType').value = sub.default_room_type;
        document.getElementById('editSubRequiresLab').checked = !!sub.requires_lab;
        document.getElementById('editSubDifficult').checked = !!sub.is_difficult;
        document.getElementById('editSubDouble').checked = !!sub.allowed_double_period;

        document.getElementById('editSubjectForm').action = "{{ url('/subjects') }}/" + sub.id + '/update';

        editSubjectModalInstance.show();
    }

    function confirmDeleteSubject(subId, subName) {
        if (confirm(`Are you sure you want to delete subject "${subName}"? This will also remove associated lessons and slots.`)) {
            const form = document.getElementById('deleteSubjectForm');
            form.action = "{{ url('/subjects') }}/" + subId;
            form.submit();
        }
    }
</script>
@endpush
