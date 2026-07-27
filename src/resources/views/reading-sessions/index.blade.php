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

    {{-- Current Session --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-clock-history me-2 text-primary"></i>Current Session
            </h6>
        </div>
        <div class="card-body">
            @if ($activeSession)
                <div class="row g-3">
                    <div class="col-md-8">
                        <h5 class="fw-semibold mb-1">{{ $activeSession->readingMaterial->title }}</h5>
                        <p class="text-muted mb-1">
                            <i class="bi bi-pencil me-1"></i>{{ optional($activeSession->readingMaterial->author)->name ?? '-' }}
                            &middot;
                            <span class="badge bg-secondary">{{ optional($activeSession->readingMaterial->category)->name ?? '-' }}</span>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="bi bi-clock me-1"></i>Started at {{ $activeSession->start_time?->format('H:i') ?? '—' }}
                        </p>
                    </div>
                    <div class="col-md-4 d-flex align-items-start justify-content-md-end">
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-play-fill me-1"></i>Active
                        </span>
                    </div>
                    <div class="col-12 d-flex gap-2 mt-3">
                        <button class="btn btn-outline-warning btn-sm" disabled>
                            <i class="bi bi-pause-fill me-1"></i>Pause
                        </button>
                        <button class="btn btn-outline-success btn-sm" disabled>
                            <i class="bi bi-check-lg me-1"></i>Finish Reading
                        </button>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-play-circle text-muted fs-1 d-block mb-3"></i>
                    <h6 class="fw-semibold">No active reading session.</h6>
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
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSessions as $session)
                                <tr>
                                    <td class="fw-semibold">{{ $session->readingMaterial->title }}</td>
                                    <td class="text-muted">{{ optional($session->readingMaterial->author)->name ?? '-' }}</td>
                                    <td><span class="badge bg-secondary">{{ optional($session->readingMaterial->category)->name ?? '-' }}</span></td>
                                    <td class="text-muted">
                                        {{ $session->start_time?->format('H:i') ?? '—' }}
                                    </td>
                                    <td class="text-muted">
                                        {{ $session->end_time?->format('H:i') ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">Completed</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('reading-sessions.edit', $session) }}" class="btn btn-outline-primary btn-sm me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('reading-sessions.destroy', $session) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this reading session?')">
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