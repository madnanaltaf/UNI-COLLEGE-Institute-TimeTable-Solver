@extends('layouts.app')
@section('title', 'Rooms & Laboratories | Timetable Solver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Classrooms & Science Laboratories</h3>
            <p class="text-muted small mb-0">Manage 21 base section homerooms and specialized laboratory facilities.</p>
        </div>
        <button class="btn btn-primary btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            <i class="fa-solid fa-plus me-1"></i> Add Facility / Room
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
                        <th>Facility Name</th>
                        <th>Room Classification</th>
                        <th>Building / Wing</th>
                        <th class="text-center">Student Capacity</th>
                        <th class="text-center">Assigned Homeroom</th>
                        <th class="text-center">Weekly Scheduled Slots</th>
                        <th class="text-end" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                        <tr>
                            <td>
                                <strong>{{ $room->name }}</strong>
                            </td>
                            <td>
                                @if($room->room_type !== 'General Classroom')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        <i class="fa-solid fa-flask-vial me-1"></i> {{ $room->room_type }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark border">{{ $room->room_type }}</span>
                                @endif
                            </td>
                            <td><span class="text-muted small">{{ $room->building }}</span></td>
                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $room->capacity }} Seats</span></td>
                            <td class="text-center">
                                @if($room->homeSections->isNotEmpty())
                                    <span class="badge bg-primary">{{ $room->homeSections->pluck('name')->implode(', ') }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info border border-info-subtle fw-bold">
                                    {{ $room->timetable_slots_count }} Slots
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 fw-semibold"
                                        onclick="openEditRoomModal({{ $room->toJson() }})">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2 fw-semibold ms-1"
                                        onclick="confirmDeleteRoom({{ $room->id }}, '{{ addslashes($room->name) }}')">
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

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-door-open me-2"></i> Add Classroom or Laboratory
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('rooms.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Room Name</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Robotics & AI Lab" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Classification</label>
                            <select name="room_type" class="form-select form-select-sm">
                                <option value="General Classroom">General Classroom</option>
                                <option value="Computer Lab">Computer Lab</option>
                                <option value="Physics Lab">Physics Lab</option>
                                <option value="Chemistry Lab">Chemistry Lab</option>
                                <option value="Biology Lab">Biology Lab</option>
                                <option value="Fine Arts Studio">Fine Arts Studio</option>
                                <option value="Central Library">Central Library</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Capacity (Seats)</label>
                            <input type="number" name="capacity" class="form-control form-control-sm" value="45" min="10" max="150" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Building Wing / Block</label>
                        <input type="text" name="building" class="form-control form-control-sm" placeholder="e.g. Academic Block A" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="fa-solid fa-check me-1"></i> Create Facility
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Facility / Room
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editRoomForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editRoomId">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Room Name</label>
                        <input type="text" id="editRoomName" name="name" class="form-control form-control-sm" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Classification</label>
                            <select id="editRoomType" name="room_type" class="form-select form-select-sm">
                                <option value="General Classroom">General Classroom</option>
                                <option value="Computer Lab">Computer Lab</option>
                                <option value="Physics Lab">Physics Lab</option>
                                <option value="Chemistry Lab">Chemistry Lab</option>
                                <option value="Biology Lab">Biology Lab</option>
                                <option value="Fine Arts Studio">Fine Arts Studio</option>
                                <option value="Central Library">Central Library</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Capacity (Seats)</label>
                            <input type="number" id="editRoomCapacity" name="capacity" class="form-control form-control-sm" min="10" max="150" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Building Wing / Block</label>
                        <input type="text" id="editRoomBuilding" name="building" class="form-control form-control-sm" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="fa-solid fa-check me-1"></i> Update Facility
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteRoomForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    let editRoomModalInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('editRoomModal');
        if (modalEl) {
            editRoomModalInstance = new bootstrap.Modal(modalEl);
        }
    });

    function openEditRoomModal(room) {
        if (!editRoomModalInstance) {
            editRoomModalInstance = new bootstrap.Modal(document.getElementById('editRoomModal'));
        }

        document.getElementById('editRoomId').value = room.id;
        document.getElementById('editRoomName').value = room.name;
        document.getElementById('editRoomType').value = room.room_type;
        document.getElementById('editRoomCapacity').value = room.capacity;
        document.getElementById('editRoomBuilding').value = room.building;

        document.getElementById('editRoomForm').action = "{{ url('/rooms') }}/" + room.id + '/update';

        editRoomModalInstance.show();
    }

    function confirmDeleteRoom(roomId, roomName) {
        if (confirm(`Are you sure you want to delete room "${roomName}"? This will detach homeroom assignments and slots.`)) {
            const form = document.getElementById('deleteRoomForm');
            form.action = "{{ url('/rooms') }}/" + roomId;
            form.submit();
        }
    }
</script>
@endpush
