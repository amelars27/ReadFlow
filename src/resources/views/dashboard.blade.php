@extends('layouts.readflow')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-25 p-3 rounded-3">
                            <i class="bi bi-book-half text-white fs-2"></i>
                        </div>
                        <div class="text-white">
                            <h4 class="mb-1 fw-bold">Welcome back, {{ Auth::user()->name }}!</h4>
                            <p class="mb-0 opacity-75">Here is your reading overview.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-info bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-book text-info fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold text-info">{{ $totalMaterials }}</h3>
                            <small class="text-muted">Reading Materials</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-clock-history text-success fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold text-success">{{ $totalSessions }}</h3>
                            <small class="text-muted">Reading Sessions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-warning bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-journal-text text-warning fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold text-warning">{{ $totalNotes }}</h3>
                            <small class="text-muted">Reading Notes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-bullseye text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold text-primary">{{ $activeGoals }}</h3>
                            <small class="text-muted">Active Goals</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h6 class="mb-0 fw-semibold">Overall Reading Progress</h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center py-4">
                    @php
                        $progressClass = match (true) {
                            $averageProgress >= 100 => 'bg-success',
                            $averageProgress >= 50 => 'bg-primary',
                            $averageProgress >= 25 => 'bg-info',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <div class="display-3 fw-bold mb-2">{{ $averageProgress }}%</div>
                    <div class="w-100 mb-2" style="max-width: 200px;">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                 style="width: {{ $averageProgress }}%"
                                 aria-valuenow="{{ $averageProgress }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Average goal completion</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h6 class="mb-0 fw-semibold">Reading by Category</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center py-3">
                    @if ($materialsByCategory->count())
                        <canvas id="categoryChart" height="200"></canvas>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-pie-chart fs-1 d-block mb-2"></i>
                            <p class="mb-0 small">No materials yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom">
                    <h6 class="mb-0 fw-semibold">Reading Activity (7 Days)</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center py-3">
                    @if ($sessionCounts->sum() > 0)
                        <canvas id="activityChart" height="200"></canvas>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-graph-up fs-1 d-block mb-2"></i>
                            <p class="mb-0 small">No sessions this week.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($activeReadingGoals->count())
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">Active Reading Goals</h6>
                        <a href="{{ route('reading-goals.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($activeReadingGoals as $goal)
                                @php
                                    $progress = $goal->target_value > 0
                                        ? min(100, round(($goal->current_value / $goal->target_value) * 100))
                                        : 0;
                                    $progressBarClass = match (true) {
                                        $progress >= 100 => 'bg-success',
                                        $progress >= 50 => 'bg-primary',
                                        $progress >= 25 => 'bg-info',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="mb-0 fw-semibold">{{ $goal->readingMaterial->title }}</h6>
                                                <span class="badge bg-primary">{{ ucfirst($goal->status) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted">{{ ucfirst($goal->goal_type) }}</small>
                                                <small class="text-muted">{{ $goal->current_value }} / {{ $goal->target_value }}</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar {{ $progressBarClass }}"
                                                         role="progressbar"
                                                         style="width: {{ $progress }}%"
                                                         aria-valuenow="{{ $progress }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $progress }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4 mt-2">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Recent Reading Sessions</h6>
                    <a href="{{ route('reading-sessions.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
                <div class="card-body p-0">
                    @if ($recentSessions->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Material</th>
                                        <th>Duration</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentSessions as $session)
                                        <tr>
                                            <td class="fw-semibold">{{ $session->readingMaterial->title }}</td>
                                            <td>{{ $session->duration_minutes }} min</td>
                                            <td class="text-muted">{{ $session->session_date->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history fs-1 d-block mb-3"></i>
                            <p class="mb-0">No sessions yet.</p>
                            <a href="{{ route('reading-sessions.create') }}" class="btn btn-primary btn-sm mt-2">Log a Session</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Recent Reading Notes</h6>
                    <a href="{{ route('reading-notes.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
                <div class="card-body p-0">
                    @if ($recentNotes->count())
                        <div class="list-group list-group-flush">
                            @foreach ($recentNotes as $note)
                                <div class="list-group-item border-0 border-bottom px-4 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1 me-3">
                                            <h6 class="mb-1 fw-semibold">{{ $note->readingMaterial->title }}</h6>
                                            <p class="mb-0 text-muted small">{{ Str::limit($note->insight, 100) }}</p>
                                        </div>
                                        <small class="text-muted flex-shrink-0">{{ $note->created_at->format('M d') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-journal-text fs-1 d-block mb-3"></i>
                            <p class="mb-0">No notes yet.</p>
                            <a href="{{ route('reading-notes.create') }}" class="btn btn-primary btn-sm mt-2">Write a Note</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($materialsByCategory->count())
                new Chart(document.getElementById('categoryChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($materialsByCategory->keys()),
                        datasets: [{
                            data: @json($materialsByCategory->values()),
                            backgroundColor: ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#198754', '#0dcaf0'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { boxWidth: 12, padding: 12, font: { size: 11 } }
                            }
                        },
                        cutout: '65%',
                    }
                });
            @endif

            @if ($sessionCounts->sum() > 0)
                new Chart(document.getElementById('activityChart'), {
                    type: 'line',
                    data: {
                        labels: @json($days->map(fn($d) => \Carbon\Carbon::parse($d)->format('D'))),
                        datasets: [{
                            label: 'Sessions',
                            data: @json($sessionCounts),
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#0d6efd',
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { size: 11 } },
                                grid: { color: 'rgba(0,0,0,0.05)' },
                            },
                            x: {
                                ticks: { font: { size: 11 } },
                                grid: { display: false },
                            }
                        }
                    }
                });
            @endif
        });
    </script>
    <style>
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important; }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        }
    </style>
@endpush
