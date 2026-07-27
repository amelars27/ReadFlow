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
                    $material = $currentSession->readingMaterial;
                    $startTime = $currentSession->start_time?->format('H:i:s') ?? '00:00:00';
                    $endTime = $currentSession->end_time?->format('H:i:s') ?? '';
                @endphp

                <div id="timer-section"
                     data-status="{{ $currentSession->status }}"
                     data-start="{{ $startTime }}"
                     data-end="{{ $endTime }}">

                    <h4 class="fw-bold mb-1">{{ $material->title }}</h4>
                    <p class="text-muted mb-4">
                        <i class="bi bi-pencil me-1"></i>{{ optional($material->author)->name ?? '-' }}
                        &middot;
                        <span class="badge bg-secondary">{{ optional($material->category)->name ?? '-' }}
                    </p>

                    <div class="display-1 fw-bold my-4" id="timer-display">
                        00:00:00
                    </div>

                    <div class="d-flex justify-content-center gap-3 mt-3">
                        @if ($currentSession->status === 'Active')
                            <form action="{{ route('reading-sessions.pause', $currentSession) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-lg px-4">
                                    <i class="bi bi-pause-fill me-2"></i>Pause
                                </button>
                            </form>
                        @elseif ($currentSession->status === 'Paused')
                            <form action="{{ route('reading-sessions.resume', $currentSession) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-lg px-4">
                                    <i class="bi bi-play-fill me-2"></i>Resume
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('reading-sessions.finish', $currentSession) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="bi bi-check-lg me-2"></i>Finish Reading
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="py-4">
                    <i class="bi bi-book text-muted" style="font-size: 4rem;"></i>
                    <h5 class="fw-semibold mt-3 mb-1">No Active Reading Session</h5>
                    <p class="text-muted mb-0">Start reading from Reading Materials.</p>
                    <a href="{{ route('reading-materials.index') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-book me-1"></i>Reading Materials
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
                                <th>Author</th>
                                <th>Category</th>
                                <th>Started At</th>
                                <th>Finished At</th>
                                <th>Duration</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSessions as $session)
                                <tr>
                                    <td class="fw-semibold">{{ $session->readingMaterial->title }}</td>
                                    <td class="text-muted">{{ optional($session->readingMaterial->author)->name ?? '-' }}</td>
                                    <td><span class="badge bg-secondary">{{ optional($session->readingMaterial->category)->name ?? '-' }}</span></td>
                                    <td class="text-muted">{{ $session->start_time?->format('H:i') ?? '—' }}</td>
                                    <td class="text-muted">{{ $session->end_time?->format('H:i') ?? '—' }}</td>
                                    <td>
                                        @if ($session->duration_minutes)
                                            {{ $session->duration_minutes }} min
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
    const startStr = section.dataset.start;
    const endStr = section.dataset.end;
    const display = document.getElementById('timer-display');

    function parseTime(str) {
        const parts = str.split(':');
        const now = new Date();
        now.setHours(parseInt(parts[0], 10));
        now.setMinutes(parseInt(parts[1], 10));
        now.setSeconds(parseInt(parts[2] || '0', 10));
        now.setMilliseconds(0);
        return now;
    }

    function formatElapsed(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        return String(h).padStart(2, '0') + ':' +
               String(m).padStart(2, '0') + ':' +
               String(s).padStart(2, '0');
    }

    function getElapsedSeconds() {
        const now = new Date();
        const start = parseTime(startStr);

        if (status === 'Paused' && endStr) {
            const end = parseTime(endStr);
            return (end - start) / 1000;
        }

        if (status === 'Active') {
            let elapsed = (now - start) / 1000;
            if (elapsed < 0) elapsed = 0;
            return elapsed;
        }

        return 0;
    }

    let elapsed = getElapsedSeconds();
    display.textContent = formatElapsed(elapsed);

    if (status === 'Active') {
        setInterval(function() {
            elapsed += 1;
            display.textContent = formatElapsed(elapsed);
        }, 1000);
    }
})();
</script>
@endpush