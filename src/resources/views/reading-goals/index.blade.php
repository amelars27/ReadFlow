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
                    $maxPage = $goal->readingSessions->max('end_page') ?? 0;
                    $progress = $totalPages > 0 ? min(100, round(($maxPage / $totalPages) * 100)) : 0;
                    $remaining = $totalPages > 0 ? max(0, $totalPages - $maxPage) : 0;
                    $progressBarClass = match (true) {
                        $progress >= 100 => 'bg-success',
                        $progress >= 50 => 'bg-primary',
                        $progress >= 25 => 'bg-info',
                        default => 'bg-secondary',
                    };

                    $sessions = $goal->readingSessions;
                    $totalSessions = $sessions->count();
                    $lastSession = $sessions->sortByDesc('created_at')->first();
                    $totalMinutes = $sessions->sum('duration_minutes');
                    $totalHrs = intdiv($totalMinutes, 60);
                    $totalMins = $totalMinutes % 60;
                @endphp

                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="card border-0 shadow-sm h-100"
                         data-href="{{ route('reading-goals.show', $goal) }}"
                         role="button"
                         style="cursor: pointer;">
                        <div class="card-body d-flex flex-column p-3">

                            {{-- 1. Book Information --}}
                            <div class="text-center mb-3">
                                @if ($material->cover_image)
                                    <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center mx-auto mb-2 w-100" style="height: 120px;">
                                        <img src="{{ Storage::url($material->cover_image) }}"
                                             alt="{{ $material->title }} cover"
                                             class="img-fluid h-100 w-100"
                                             style="object-fit: cover;">
                                    </div>
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mx-auto mb-2 w-100" style="height: 120px;">
                                        <i class="bi bi-book-half fs-1 text-primary"></i>
                                    </div>
                                @endif
                                <h6 class="fw-semibold text-center mb-1 text-truncate" title="{{ $material->title }}">{{ $material->title }}</h6>
                                @if ($material->description)
                                    <p class="small text-muted text-center mb-1 px-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $material->description }}
                                    </p>
                                @endif
                                <p class="small text-muted text-center mb-1">{{ $material->author->name }}</p>
                                <span class="badge bg-secondary">{{ $material->category->name }}</span>
                            </div>

                            {{-- 2. Reading Progress --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>{{ $maxPage }} / {{ $totalPages ?: '—' }} pages</span>
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

                            {{-- 3. Goal Information --}}
                            <div class="d-flex justify-content-between align-items-center mb-3 small">
                                <div>
                                    <i class="bi bi-calendar3 text-muted me-1"></i>
                                    <span class="text-muted">Target:</span>
                                    <span class="fw-semibold">{{ $goal->end_date->format('M d, Y') }}</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-{{ $goal->status === 'completed' ? 'success' : 'primary' }}">
                                        {{ ucfirst($goal->status) }}
                                    </span>
                                    <span class="badge bg-secondary">{{ ucfirst($goal->goal_type) }}</span>
                                </div>
                            </div>

                            {{-- 4. Reading Sessions --}}
                            <div class="bg-light rounded-3 p-2 mb-3 small">
                                <div class="fw-semibold mb-2">
                                    <i class="bi bi-clock-history me-1"></i>Reading Sessions
                                </div>
                                @if ($totalSessions > 0)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Total Sessions:</span>
                                        <span class="fw-semibold">{{ $totalSessions }}</span>
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
                                    @if ($lastSession)
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Last Session:</span>
                                            <span class="fw-semibold">
                                                @if ($lastSession->duration_minutes)
                                                    {{ $lastSession->duration_minutes }} min
                                                @elseif ($lastSession->total_seconds)
                                                    {{ max(0, intdiv($lastSession->total_seconds, 60)) }} min
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center text-muted py-1">No reading sessions yet.</div>
                                @endif
                            </div>

                            {{-- 5. Action Buttons --}}
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <a href="{{ route('reading-materials.show', $material) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-book me-1"></i>View Book
                                </a>
                                <form action="{{ route('reading-sessions.start', $goal) }}" method="POST">
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

@push('scripts')
<script>
    document.querySelectorAll('.card[data-href]').forEach(function(card) {
        card.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form') || e.target.closest('input')) {
                return;
            }
            window.location.href = card.dataset.href;
        });
    });
</script>
@endpush