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

                        <form action="{{ route('reading-sessions.finish', $currentSession) }}" method="POST">
                            @csrf
                            <input type="hidden" name="elapsed_seconds" id="finish-elapsed" value="0">
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="bi bi-check-lg me-2"></i>Finish Reading
                            </button>
                        </form>
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
                    <a href="{{ route('reading-materials.index') }}" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-book me-2"></i>Choose Reading Material
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Sessions --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-check2-circle me-2 text-success"></i>Recent Sessions
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($recentSessions->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Reading Material</th>
                                <th>Date</th>
                                <th>Started</th>
                                <th>Finished</th>
                                <th>Duration</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSessions as $session)
                                <tr>
                                    <td class="fw-semibold">{{ $session->readingGoal?->readingMaterial?->title ?? '—' }}</td>
                                    <td class="text-muted">{{ $session->session_date?->format('M j, Y') ?? '—' }}</td>
                                    <td class="text-muted">{{ $session->start_time?->format('g:i A') ?? '—' }}</td>
                                    <td class="text-muted">{{ $session->end_time?->format('g:i A') ?? '—' }}</td>
                                    <td>
                                        @if ($session->duration_minutes)
                                            @php
                                                $d = $session->duration_minutes;
                                                $hrs = intdiv($d, 60);
                                                $mins = $d % 60;
                                            @endphp
                                            @if ($hrs > 0)
                                                {{ $hrs }}h {{ $mins }}m
                                            @else
                                                {{ $mins }} min
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('reading-sessions.destroy', $session) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Delete this reading session?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-clock-history fs-1 d-block mb-3"></i>
                    <p class="mb-0">No completed reading sessions yet.</p>
                    <a href="{{ route('reading-materials.index') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-book me-1"></i>Browse Reading Materials
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
        const finishInput = document.getElementById('finish-elapsed');
        if (pauseInput) pauseInput.value = secs;
        if (finishInput) finishInput.value = secs;
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