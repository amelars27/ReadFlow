@extends('layouts.readflow')

@section('title', 'Reading Goals')

@section('header', 'Reading Goals')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0">
            <i class="bi bi-bullseye me-1"></i>{{ $readingGoals->total() }} goal{{ $readingGoals->total() !== 1 ? 's' : '' }}
        </p>
        <a href="{{ route('reading-goals.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add New
        </a>
    </div>

    @if ($readingGoals->count())
        <div class="row g-4">
            @foreach ($readingGoals as $goal)
                @php
                    $material = $goal->readingMaterial;
                    $totalPages = $material->total_pages ?? 0;
                    $currentPage = $material->current_page ?? 0;
                    $progress = $totalPages > 0 ? min(100, round(($currentPage / $totalPages) * 100)) : 0;
                    $remaining = $totalPages > 0 ? max(0, $totalPages - $currentPage) : 0;
                    $progressBarClass = match (true) {
                        $progress >= 100 => 'bg-success',
                        $progress >= 50 => 'bg-primary',
                        $progress >= 25 => 'bg-info',
                        default => 'bg-secondary',
                    };

                    $sessions = $material->readingSessions;
                    $totalSessions = $sessions->count();
                    $lastSession = $sessions->sortByDesc('created_at')->first();
                    $lastDuration = $lastSession?->duration_minutes;
                    $totalSeconds = $sessions->sum('total_seconds');
                    $totalMin = intdiv($totalSeconds, 60);
                    $totalHrs = intdiv($totalMin, 60);
                    $totalMins = $totalMin % 60;

                    $goalStatusBadge = $goal->status === 'completed' ? 'success' : 'primary';
                    $readingStatusBadge = match ($material->status->value) {
                        'Completed' => 'success',
                        'Reading' => 'warning',
                        default => 'secondary',
                    };
                @endphp

                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3">
                                @if ($material->cover_image)
                                    <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center mx-auto" style="width: 72px; height: 72px;">
                                        <img src="{{ Storage::url($material->cover_image) }}"
                                             alt="{{ $material->title }} cover"
                                             class="img-fluid"
                                             style="width: 72px; height: 72px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="bg-light rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                        <i class="bi bi-book-half fs-2 text-primary"></i>
                                    </div>
                                @endif
                            </div>

                            <h6 class="fw-semibold text-center mb-1">{{ $material->title }}</h6>
                            <p class="small text-muted text-center mb-3">{{ $material->author->name }}</p>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>{{ $currentPage }} / {{ $totalPages ?: '—' }} pages</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $progressBarClass }}"
                                         role="progressbar"
                                         style="width: {{ $progress }}%"
                                         aria-valuenow="{{ $progress }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                @if ($remaining > 0)
                                    <div class="text-end small text-muted mt-1">{{ $remaining }} pages remaining</div>
                                @elseif ($totalPages > 0)
                                    <div class="text-end small text-success mt-1">All pages read</div>
                                @endif
                            </div>

                            <div class="bg-light rounded-3 p-2 mb-3 small">
                                @if ($totalSessions > 0)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Last Session:</span>
                                        <span class="fw-semibold">{{ $lastDuration }} min</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Total Time:</span>
                                        <span class="fw-semibold">
                                            @if ($totalHrs > 0)
                                                {{ $totalHrs }}h {{ $totalMins }}m
                                            @else
                                                {{ $totalMins }} min
                                            @endif
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Sessions:</span>
                                        <span class="fw-semibold">{{ $totalSessions }}</span>
                                    </div>
                                @else
                                    <div class="text-center text-muted">No reading sessions yet.</div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
                                <span class="badge bg-{{ $goalStatusBadge }}">{{ ucfirst($goal->status) }}</span>
                                <span class="badge bg-{{ $readingStatusBadge }}">{{ $material->status->value }}</span>
                                <span class="badge bg-secondary">{{ ucfirst($goal->goal_type) }}</span>
                            </div>

                            <div class="text-center small text-muted mb-3">
                                <i class="bi bi-calendar3 me-1"></i>Target: {{ $goal->end_date->format('M d, Y') }}
                            </div>

                            <div class="mt-auto d-flex justify-content-center gap-2 flex-wrap">
                                <a href="{{ route('reading-materials.show', $material) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-book me-1"></i>View Book
                                </a>
                                <form action="{{ route('reading-sessions.start', $material) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-play-fill me-1"></i>Start Reading
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($readingGoals->hasPages())
            <div class="mt-4">
                {{ $readingGoals->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bullseye fs-1 d-block mb-3"></i>
            <p class="mb-0">No reading goals yet.</p>
            <a href="{{ route('reading-goals.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-lg me-1"></i>Create Your First Reading Goal
            </a>
        </div>
    @endif
@endsection