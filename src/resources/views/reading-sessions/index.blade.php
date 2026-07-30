@extends('layouts.readflow')

@section('title', 'Reading Sessions')

@section('header', 'Reading Sessions')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Focus Timer Section --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center py-5">
            @if ($currentSession)
                @php
                    $material = $currentSession->readingGoal?->readingMaterial;
                    $elapsed = $currentSession->total_seconds;
                    if ($currentSession->status === 'Active') {
                        $elapsed += now()->diffInSeconds($currentSession->updated_at);
                    }
                    if ($elapsed < 0) $elapsed = 0;
                @endphp

                <div id="timer-section"
                     data-status="{{ $currentSession->status }}"
                     data-elapsed="{{ $elapsed }}">

                    <h4 class="fw-bold mb-1">{{ $material?->title ?? 'Unknown' }}</h4>
                    <p class="text-muted mb-4">
                        <i class="bi bi-pencil me-1"></i>{{ $material?->author?->name ?? '-' }}
                        &middot;
                        <span class="badge bg-secondary">{{ $material?->category?->name ?? '-' }}</span>
                    </p>

                    <div class="display-1 fw-bold my-4" id="timer-display">
                        00:00:00
                    </div>

                    <div class="d-flex justify-content-center gap-3 mt-3">
                        @if ($currentSession->status === 'Active')
                            <form action="{{ route('reading-sessions.pause', $currentSession) }}" method="POST">
                                @csrf
                                <input type="hidden" name="elapsed_seconds" id="pause-elapsed" value="0">
                                <button type="submit" class="btn btn-outline-warning btn-lg px-4">
                                    <i class="bi bi-pause-fill me-2"></i>Pause
                                </button>
                            </form>
                        @elseif ($currentSession->status === 'Paused')
                            <form action="{{ route('reading-sessions.resume', $currentSession) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-lg px-4">
                                    <i class="bi bi-play-fill me-2"></i>Continue
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('reading-sessions.confirm-finish', $currentSession) }}"
                               id="finish-link"
                               class="btn btn-success btn-lg px-4">
                                <i class="bi bi-check-lg me-2"></i>Finish Reading
                            </a>
                    </div>
                </div>
            @else
                <div class="py-5">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-hourglass-split" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="fw-semibold mb-2">Ready to Read?</h4>
                    <p class="text-muted mb-3 mx-auto" style="max-width: 400px;">
                        Start a focused reading session to track your time and build a consistent reading habit.
                    </p>
                    <a href="{{ route('reading-goals.index') }}" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-bullseye me-2"></i>Choose Reading Goal
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .session-bar:hover { background-color: #f8f9fa; }
        .session-bar:last-child { border-bottom: none !important; }
    </style>

    {{-- Recent Sessions --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-check2-circle me-2 text-success"></i>Recent Sessions
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($recentSessions->count())
                @foreach ($recentSessions as $session)
                    @php
                        $mat = $session->readingGoal?->readingMaterial;
                        $dMin = $session->duration_minutes;
                        $dSec = $session->total_seconds;
                        $displayMin = $dMin ?? ($dSec > 0 ? intdiv($dSec, 60) : null);
                        $hrs = $displayMin !== null ? intdiv($displayMin, 60) : 0;
                        $mins = $displayMin !== null ? $displayMin % 60 : 0;
                    @endphp
                    <div class="px-4 py-3 border-bottom session-bar">
                        <div class="row align-items-center g-2">
                            <div class="col-lg-2 col-md-3 text-center text-md-start mb-2 mb-md-0">
                                <div class="d-inline-flex align-items-center gap-2 bg-primary bg-opacity-10 text-primary rounded-3 px-3 py-2 fw-bold" style="font-size: 1.25rem;">
                                    <i class="bi bi-clock"></i>
                                    @if ($displayMin !== null)
                                        @if ($hrs > 0)
                                            {{ $hrs }}h {{ $mins }}m
                                        @else
                                            {{ $mins }}m
                                        @endif
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-4 mb-2 mb-md-0">
                                <div class="fw-semibold text-dark">{{ $mat?->title ?? '—' }}</div>
                                <small class="text-muted"><i class="bi bi-pencil me-1"></i>{{ $mat?->author?->name ?? '—' }}</small>
                            </div>
                            <div class="col-lg-3 col-md-3 mb-2 mb-md-0">
                                <div class="small">
                                    @if ($session->start_page && $session->end_page)
                                        <span class="text-primary fw-semibold">
                                            <i class="bi bi-file-text me-1"></i>Page {{ $session->start_page }} → {{ $session->end_page }}
                                        </span>
                                        <br>
                                    @endif
                                    <span class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $session->session_date?->format('M j, Y') ?? '—' }}
                                    </span>
                                    <span class="text-muted ms-2">
                                        <i class="bi bi-clock"></i> {{ $session->start_time?->format('g:i A') ?? '—' }} – {{ $session->end_time?->format('g:i A') ?? '—' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 text-lg-end text-center">
                                <form action="{{ route('reading-sessions.destroy', $session) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3"
                                            onclick="return confirm('Delete this reading session?')">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-clock-history fs-1 d-block mb-3"></i>
                    <p class="mb-0">No completed reading sessions yet.</p>
                    <a href="{{ route('reading-goals.index') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-bullseye me-1"></i>Browse Reading Goals
                    </a>
                </div>
            @endif
        </div>
        @if ($recentSessions->hasPages())
            <div class="card-footer bg-transparent border-top">
                {{ $recentSessions->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
(function() {
    const section = document.getElementById('timer-section');
    if (!section) return;

    const status = section.dataset.status;
    let elapsed = parseInt(section.dataset.elapsed, 10) || 0;
    if (elapsed < 0) elapsed = 0;
    const display = document.getElementById('timer-display');

    function formatElapsed(seconds) {
        if (seconds < 0) seconds = 0;
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        return String(h).padStart(2, '0') + ':' +
               String(m).padStart(2, '0') + ':' +
               String(s).padStart(2, '0');
    }

    display.textContent = formatElapsed(elapsed);

    function syncElapsed() {
        const secs = Math.floor(elapsed);
        const pauseInput = document.getElementById('pause-elapsed');
        if (pauseInput) pauseInput.value = secs;
        const finishLink = document.getElementById('finish-link');
        if (finishLink) {
            const base = finishLink.href.split('?')[0];
            finishLink.href = base + '?elapsed=' + secs;
        }
    }
    syncElapsed();

    if (status === 'Active') {
        setInterval(function() {
            elapsed += 1;
            display.textContent = formatElapsed(elapsed);
            syncElapsed();
        }, 1000);
    }
})();
</script>
@endpush