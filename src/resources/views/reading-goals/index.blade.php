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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">All Reading Goals</h6>
            <a href="{{ route('reading-goals.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Add New
            </a>
        </div>
        <div class="card-body p-0">
            @if ($readingGoals->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Goal Type</th>
                                <th>Target</th>
                                <th>Current</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>End Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($readingGoals as $goal)
                                <tr>
                                    <td class="fw-semibold">{{ $goal->readingMaterial->title }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($goal->goal_type) }}</span></td>
                                    @php
                                        $totalPages = $goal->readingMaterial->total_pages ?? 0;
                                        $currentPage = $goal->readingMaterial->current_page ?? 0;
                                        $progress = $totalPages > 0
                                            ? min(100, round(($currentPage / $totalPages) * 100))
                                            : 0;
                                        $progressBarClass = match (true) {
                                            $progress >= 100 => 'bg-success',
                                            $progress >= 50 => 'bg-primary',
                                            $progress >= 25 => 'bg-info',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <td>{{ $totalPages ?: '—' }}</td>
                                    <td>{{ $currentPage }}</td>
                                    <td style="min-width: 140px;">
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
                                    </td>
                                    <td>
                                        @php
                                            $statusBadge = $goal->status === 'completed' ? 'success' : 'primary';
                                        @endphp
                                        <span class="badge bg-{{ $statusBadge }}">{{ ucfirst($goal->status) }}</span>
                                    </td>
                                    <td class="text-muted">{{ $goal->end_date->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('reading-goals.edit', $goal) }}" class="btn btn-outline-primary btn-sm me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('reading-goals.destroy', $goal) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this reading goal?')">
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
                    <i class="bi bi-bullseye fs-1 d-block mb-3"></i>
                    <p class="mb-0">No reading goals yet.</p>
                    <a href="{{ route('reading-goals.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-lg me-1"></i>Create Your First Reading Goal
                    </a>
                </div>
            @endif
        </div>
        @if ($readingGoals->hasPages())
            <div class="card-footer bg-transparent border-top">
                {{ $readingGoals->links() }}
            </div>
        @endif
    </div>
@endsection
