@extends('layouts.readflow')

@section('title', $readingGoal->readingMaterial->title . ' — Reading Goal')

@section('header', 'Reading Goal')

@section('content')
    @php
        $material = $readingGoal->readingMaterial;
        $totalPages = $material->total_pages ?? 0;
        $maxPage = $readingGoal->readingSessions->max('end_page') ?? 0;
        $progress = $totalPages > 0 ? min(100, round(($maxPage / $totalPages) * 100)) : 0;
        $remaining = $totalPages > 0 ? max(0, $totalPages - $maxPage) : 0;
        $progressBarClass = match (true) {
            $progress >= 100 => 'bg-success',
            $progress >= 50 => 'bg-primary',
            $progress >= 25 => 'bg-info',
            default => 'bg-secondary',
        };

        $sessions = $readingGoal->readingSessions;
        $totalSessions = $sessions->count();
        $lastSession = $sessions->sortByDesc('created_at')->first();
        $totalMinutes = $sessions->sum('duration_minutes');
        $totalHrs = intdiv($totalMinutes, 60);
        $totalMins = $totalMinutes % 60;

        $recentNotes = $material->readingNotes()->latest()->take(3)->get();

        $timeline = [];

        $timeline[] = [
            'icon' => 'bi-bullseye',
            'title' => 'Reading Goal Created',
            'description' => 'Started reading goal for ' . $material->title,
            'time' => $readingGoal->created_at,
        ];

        foreach ($readingGoal->readingSessions as $session) {
            $timeline[] = [
                'icon' => 'bi-play-circle',
                'title' => 'Reading Session',
                'description' => $session->duration_minutes
                    ? 'Read for ' . $session->duration_minutes . ' min'
                    : ($session->total_seconds
                        ? 'Read for ' . max(0, intdiv($session->total_seconds, 60)) . ' min'
                        : 'Session completed'),
                'time' => $session->created_at,
            ];
        }

        $allNotes = $material->readingNotes;
        foreach ($allNotes as $note) {
            $timeline[] = [
                'icon' => 'bi-journal-text',
                'title' => 'Reading Note: ' . Str::limit($note->title, 40),
                'description' => Str::limit($note->summary ?? $note->insight ?? 'No summary', 60),
                'time' => $note->created_at,
            ];
        }

        usort($timeline, function ($a, $b) {
            return $b['time'] <=> $a['time'];
        });
    @endphp

    <div class="row g-4">
        <div class="col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    @if ($material->cover_image)
                        <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center w-100" style="height: 260px;">
                            <img src="{{ Storage::url($material->cover_image) }}"
                                 alt="{{ $material->title }} cover"
                                 class="img-fluid h-100 w-100"
                                 style="object-fit: cover;">
                        </div>
                    @else
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center w-100" style="height: 260px;">
                            <i class="bi bi-book-half fs-1 text-primary"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">{{ $material->title }}</h3>

                    <div class="mb-3">
                        @if ($material->description)
                            <p class="text-muted mb-3">{{ $material->description }}</p>
                        @endif

                        <p class="mb-2">
                            <i class="bi bi-pencil text-muted me-2"></i>
                            <span>{{ $material->author->name }}</span>
                        </p>
                        <span class="badge bg-secondary">
                            <i class="bi bi-folder me-1"></i>{{ $material->category->name }}
                        </span>
                    </div>

                    <hr class="my-3">

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

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center small">
                        <div>
                            <i class="bi bi-calendar3 text-muted me-1"></i>
                            <span class="text-muted">Target:</span>
                            <span class="fw-semibold">{{ $readingGoal->end_date->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex gap-1">
                            <span class="badge bg-{{ $readingGoal->status === 'completed' ? 'success' : 'primary' }}">
                                {{ ucfirst($readingGoal->status) }}
                            </span>
                            <span class="badge bg-secondary">{{ ucfirst($readingGoal->goal_type) }}</span>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Reading Sessions --}}
                    <div class="bg-light rounded-3 p-3 mb-3 small">
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
                                        @else
                                            {{ $lastSession->created_at->diffForHumans() }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        @else
                            <div class="text-center text-muted py-3">No reading sessions yet.</div>
                        @endif
                    </div>

                    <hr class="my-3">

                    {{-- Reading Notes --}}
                    <div class="mb-3 small">
                        <div class="fw-semibold mb-2">
                            <i class="bi bi-journal-text me-1"></i>Reading Notes
                        </div>
                        @if ($recentNotes->count())
                            @foreach ($recentNotes as $note)
                                <div class="border rounded-3 p-2 mb-2">
                                    <div class="fw-semibold">{{ $note->title }}</div>
                                    <p class="text-muted mb-1 small">
                                        {{ Str::limit($note->summary ?? $note->insight, 80) }}
                                    </p>
                                    @if ($note->favorite_quote)
                                        <blockquote class="border-start border-3 ps-2 mb-1 small text-muted fst-italic" style="border-color: #dee2e6;">
                                            {{ Str::limit($note->favorite_quote, 100) }}
                                        </blockquote>
                                    @endif
                                    <div class="text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $note->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            @endforeach
                            <a href="{{ route('reading-notes.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bi bi-journal-text me-1"></i>View All Notes
                            </a>
                        @else
                            <div class="text-center text-muted py-3 bg-light rounded-3">No reading notes yet.</div>
                        @endif
                    </div>

                    <hr class="my-3">

                    {{-- Reading Timeline --}}
                    <div class="mb-3 small">
                        <div class="fw-semibold mb-2">
                            <i class="bi bi-clock me-1"></i>Reading Timeline
                        </div>
                        @if (count($timeline))
                            <div class="position-relative ps-4" style="border-left: 2px solid #dee2e6;">
                                @foreach ($timeline as $event)
                                    <div class="mb-3 position-relative">
                                        <span class="position-absolute d-flex align-items-center justify-content-center rounded-circle bg-white border"
                                              style="width: 28px; height: 28px; left: -43px; top: 0;">
                                            <i class="bi {{ $event['icon'] }} small text-muted"></i>
                                        </span>
                                        <div class="fw-semibold">{{ $event['title'] }}</div>
                                        <div class="text-muted">{{ $event['description'] }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $event['time']->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-3 bg-light rounded-3">No reading activity yet.</div>
                        @endif
                    </div>

                    <hr class="my-3">

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('reading-goals.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Goals
                        </a>
                        <a href="{{ route('reading-materials.show', $material) }}" class="btn btn-outline-primary">
                            <i class="bi bi-book me-1"></i>View Book
                        </a>
                        <form action="{{ route('reading-sessions.start', $readingGoal) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-play-fill me-1"></i>Start Reading
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection