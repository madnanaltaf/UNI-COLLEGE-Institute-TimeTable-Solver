@extends('layouts.app')
@section('title', 'Classes & Sections | Timetable Solver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Grade Classes, Sections & Class Incharges</h3>
            <p class="text-muted small mb-0">Manage 7 Grade levels (Classes 6–12), 21 Sections, and designated faculty Class Incharge roles.</p>
        </div>
    </div>

    <div class="row g-4">
        @foreach($classes as $c)
            <div class="col-lg-6">
                <div class="card card-flat h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary px-3 py-1 fs-6">{{ $c->name }}</span>
                            <span class="small text-muted fw-semibold">Order: {{ $c->order_num }}</span>
                        </div>
                        <span class="badge bg-light text-secondary border">{{ count($c->sections) }} Parallel Sections</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 70px;">Section</th>
                                        <th>Designated Class Incharge</th>
                                        <th>Base Room</th>
                                        <th style="width: 70px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($c->sections as $sec)
                                        <tr>
                                            <td><span class="badge bg-secondary fs-6">{{ $sec->name }}</span></td>
                                            <td>
                                                <form action="{{ route('classes.incharge') }}" method="POST" class="d-flex gap-2">
                                                    @csrf
                                                    <input type="hidden" name="section_id" value="{{ $sec->id }}">
                                                    <select name="teacher_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        <option value="">-- Unassigned --</option>
                                                        @foreach($teachers as $t)
                                                            <option value="{{ $t->id }}" {{ $sec->class_teacher_id == $t->id ? 'selected' : '' }}>
                                                                {{ $t->code }} — {{ $t->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <span class="small text-muted">{{ $sec->homeRoom?->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('timetable', ['mode' => 'section', 'id' => $sec->id]) }}" class="btn btn-outline-primary btn-sm p-1 px-2" title="View Section Timetable">
                                                    <i class="fa-solid fa-calendar"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
