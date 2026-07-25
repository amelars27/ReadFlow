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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">All Reading Sessions</h6>
            <a href="{{ route('reading-sessions.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Add New
            </a>
        </div>
        <div class="card-body p-0">
            @if ($readingSessions->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Reading Material</th>
                                <th>Date</th>
                                <th>Duration</th>
                                <th>Pages Read</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($readingSessions as $session)
                                <tr>
                                    <td>
                                        <a href="{{ route('reading-materials.show', $session->readingMaterial) }}" class="text-decoration-none fw-semibold">
                                            {{ $session->readingMaterial->title }}
                                        </a>
                                    </td>
                                    <td class="text-muted">{{ $session->session_date->format('M d, Y') }}</td>
                                    <td>{{ $session->duration_minutes }} min</td>
                                    <td>{{ $session->pages_read ?? '—' }}</td>
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
                    <p class="mb-0">No reading sessions yet.</p>
                    <a href="{{ route('reading-sessions.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-lg me-1"></i>Add Your First Reading Session
                    </a>
                </div>
            @endif
        </div>
        @if ($readingSessions->hasPages())
            <div class="card-footer bg-transparent border-top">
                {{ $readingSessions->links() }}
            </div>
        @endif
    </div>
@endsection
