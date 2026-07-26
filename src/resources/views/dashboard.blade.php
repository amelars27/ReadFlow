@extends('layouts.readflow')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-book-half text-primary fs-2"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">Welcome to ReadFlow</h5>
                            <p class="card-text text-muted mb-0">Track your reading journey.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded-3">
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
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-3">
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
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-3">
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
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3">
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

    @if ($activeReadingGoals->count())
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">Active Reading Goals</h6>
                        <a href="{{ route('reading-goals.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
                    </div>
                    <div class="card-body">
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
                            <div class="mb-4 {{ !$loop->last ? 'border-bottom pb-4' : 'mb-0' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $goal->readingMaterial->title }}</h6>
                                        <small class="text-muted">{{ ucfirst($goal->goal_type) }} — {{ $goal->current_value }} / {{ $goal->target_value }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ ucfirst($goal->status) }}</span>
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
