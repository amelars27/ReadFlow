@extends('layouts.readflow')

@section('title', $readingMaterial->title)

@section('header', $readingMaterial->title)

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="mb-1">{{ $readingMaterial->title }}</h5>
                    <p class="text-muted mb-0">
                        <i class="bi bi-pencil me-1"></i>{{ $readingMaterial->author->name }}
                        &middot;
                        <span class="badge bg-secondary">{{ $readingMaterial->category->name }}</span>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <form action="{{ route('reading-sessions.start', $readingMaterial) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-play-fill me-1"></i>Start Reading
                        </button>
                    </form>
                    <a href="{{ route('reading-materials.edit', $readingMaterial) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form action="{{ route('reading-materials.destroy', $readingMaterial) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this reading material?')">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase fw-semibold">Source Type</small>
                            <p class="mb-0 fw-semibold">{{ $readingMaterial->source_type->value }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase fw-semibold">Status</small>
                            <p class="mb-0">
                                @php
                                    $badge = match ($readingMaterial->status->value) {
                                        'Completed' => 'success',
                                        'Reading' => 'warning',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ $readingMaterial->status->value }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase fw-semibold">Total Pages</small>
                            <p class="mb-0 fw-semibold">{{ $readingMaterial->total_pages ?? '—' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <small class="text-muted text-uppercase fw-semibold">Source URL</small>
                            <p class="mb-0">
                                @if ($readingMaterial->source_url)
                                    <a href="{{ $readingMaterial->source_url }}" target="_blank" class="text-break">
                                        {{ $readingMaterial->source_url }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @if ($readingMaterial->description)
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <small class="text-muted text-uppercase fw-semibold">Description</small>
                                <p class="mb-0 mt-1">{{ $readingMaterial->description }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('reading-materials.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>
@endsection
