<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Institutional Timetable & Duty Solver')</title>
    <!-- Bootstrap 5 CSS (Cosmo Flat Aesthetic) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Google Fonts: Inter / Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-success: #10b981;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #2563eb;
            --sidebar-text: #94a3b8;
            --top-ribbon-bg: #ffffff;
            --app-bg: #f8fafc;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--app-bg);
            color: #1e293b;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }
        /* Layout Structure */
        #app-wrapper {
            display: flex;
            min-height: 100vh;
        }
        #sidebar {
            width: 260px;
            min-width: 260px;
            background-color: var(--sidebar-bg);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        #sidebar .brand {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        #sidebar .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.7rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.15s ease-in-out;
            border-left: 3px solid transparent;
        }
        #sidebar .nav-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }
        #sidebar .nav-link.active {
            color: #ffffff;
            background-color: rgba(37,99,235,0.15);
            border-left-color: var(--brand-primary);
            font-weight: 600;
        }
        #sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.05rem;
        }
        #main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        /* Top Action Ribbon */
        #top-ribbon {
            background-color: var(--top-ribbon-bg);
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        }
        .btn-solve {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            font-weight: 700;
            border: none;
            padding: 0.55rem 1.25rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(16,185,129,0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            letter-spacing: 0.3px;
        }
        .btn-solve:hover {
            background: linear-gradient(135deg, #059669, #047857);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(16,185,129,0.4);
        }
        .badge-solved {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
            border-radius: 20px;
        }
        .badge-unsolved {
            background-color: #fff1f2;
            color: #9f1239;
            border: 1px solid #fecdd3;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
            border-radius: 20px;
        }
        /* Page Body Container */
        .content-body {
            padding: 1.5rem;
            flex-grow: 1;
        }
        /* Modern Flat Cards */
        .card-flat {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: box-shadow 0.2s;
        }
        .card-flat:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }
        .mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app-wrapper">
        <!-- Sidebar Navigation (Dark Flat Theme) -->
        <nav id="sidebar">
            <div class="brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 1.05rem; letter-spacing: 0.5px; line-height: 1.2;">SOLVER CSP</div>
                    <div style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 600;">Institutional Edition</div>
                </div>
            </div>

            <!-- Prominent Sidebar Solve Button -->
            <div class="p-3 pb-2">
                <button class="btn btn-solve w-100 justify-content-center btn-trigger-solve py-2">
                    <i class="fa-solid fa-bolt"></i> ⚡ GENERATE TIMETABLE
                </button>
            </div>

            <div class="nav flex-column py-2 flex-grow-1">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-chart-pie"></i> 📊 Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('timetable') ? 'active' : '' }}" href="{{ route('timetable') }}">
                    <i class="fa-solid fa-calendar-check"></i> 📅 Interactive Timetable
                </a>
                <a class="nav-link {{ request()->routeIs('allotments') ? 'active' : '' }}" href="{{ route('allotments') }}">
                    <i class="fa-solid fa-book-open-reader"></i> 📖 Class Period Allotment
                </a>
                <a class="nav-link {{ request()->routeIs('faculty') ? 'active' : '' }}" href="{{ route('faculty') }}">
                    <i class="fa-solid fa-user-tie"></i> 👥 Faculty & Teachers
                </a>
                <a class="nav-link {{ request()->routeIs('classes') ? 'active' : '' }}" href="{{ route('classes') }}">
                    <i class="fa-solid fa-school"></i> 🏛️ Classes & Sections
                </a>
                <a class="nav-link {{ request()->routeIs('subjects') ? 'active' : '' }}" href="{{ route('subjects') }}">
                    <i class="fa-solid fa-layer-group"></i> 📚 Subjects & Curriculum
                </a>
                <a class="nav-link {{ request()->routeIs('rooms') ? 'active' : '' }}" href="{{ route('rooms') }}">
                    <i class="fa-solid fa-flask-vial"></i> 🔬 Rooms & Laboratories
                </a>
                <a class="nav-link {{ request()->routeIs('institution') ? 'active' : '' }}" href="{{ route('institution') }}">
                    <i class="fa-solid fa-sliders"></i> ⚙️ Institution & Calendar
                </a>
                <a class="nav-link {{ request()->routeIs('duties') ? 'active' : '' }}" href="{{ route('duties') }}">
                    <i class="fa-solid fa-shield-halved"></i> 🛡️ Staff Duty Roster
                </a>
                <a class="nav-link {{ request()->routeIs('exports') ? 'active' : '' }}" href="{{ route('exports') }}">
                    <i class="fa-solid fa-file-pdf"></i> 📄 Export Center (PDF)
                </a>
            </div>

            <div class="p-3 text-center" style="border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.75rem; color: #64748b;">
                <span class="badge bg-secondary mb-1">Laravel 13 • PHP 8.3</span><br/>
                Native CSP Backtracking Engine
            </div>
        </nav>

        <!-- Main Workspace Area -->
        <div id="main-content">
            <!-- Persistent Top Action Ribbon -->
            <header id="top-ribbon">
                <div class="d-flex align-items-center gap-3">
                    <span class="fs-5 fw-bold text-dark">{{ $setting->name }}</span>
                    <span class="badge bg-light text-secondary border px-2 py-1">AY: {{ $setting->academic_year }}</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                        6-Day Week: Mon–Thu {{ $setting->periods_per_day }}P • Fri {{ $setting->friday_periods }}P (Less) • Sat {{ $setting->getPeriodsForDay('Saturday') }}P
                    </span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    @php
                        $slotCount = \App\Models\TimetableSlot::count();
                    @endphp
                    @if($slotCount > 0)
                        <span class="badge-solved">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> ✓ Solved ({{ $slotCount }} slots)
                        </span>
                    @else
                        <span class="badge-unsolved">
                            <i class="fa-solid fa-triangle-exclamation text-danger me-1"></i> Unsolved
                        </span>
                    @endif

                    <button class="btn btn-solve btn-trigger-solve">
                        <i class="fa-solid fa-bolt"></i> [ ⚡ GENERATE TIMETABLE ]
                    </button>
                </div>
            </header>

            <!-- Flash Notifications -->
            <div class="px-4 pt-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="content-body">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Solver In-Progress Modal / Spinner -->
    <div class="modal fade" id="solverModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary mb-3" style="width: 3.5rem; height: 3.5rem;" role="status"></div>
                    <h5 class="fw-bold mb-1">Constraint Satisfaction Solver Active</h5>
                    <p class="text-muted small mb-0">Executing pure PHP backtracking with MRV heuristic, forward checking, and multi-section laboratory synchronization...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const solverModal = new bootstrap.Modal(document.getElementById('solverModal'));
            const solveButtons = document.querySelectorAll('.btn-trigger-solve');

            solveButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    solverModal.show();

                    fetch("{{ route('solve') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        solverModal.hide();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '⚡ Timetable Solved!',
                                html: `<b>${data.slots_assigned} Slots Conflict-Free Scheduled</b><br/>Search Duration: <code>${data.duration_ms} ms</code><br/>Parallel Lab Macro-Groups Aligned: <b>${data.sync_groups_aligned}</b><br/>Staff Duties Optimized: <b>${data.duties_assigned}</b>`,
                                confirmButtonColor: '#10b981',
                                confirmButtonText: 'View Interactive Timetable'
                            }).then(() => {
                                window.location.href = "{{ route('timetable') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Solving Failed',
                                text: data.message || 'Could not find a conflict-free solution with current constraints.',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    })
                    .catch(err => {
                        solverModal.hide();
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'An unexpected error occurred while executing the solver pipeline.',
                            confirmButtonColor: '#ef4444'
                        });
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
