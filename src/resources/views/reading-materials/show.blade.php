@extends('layouts.readflow')

@section('title', $readingMaterial->title . ' — Reading Materials')

@section('header', $readingMaterial->title)

@section('content')
    <div class="row g-4">
        <div class="col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="bg-light rounded-3 d-inline-flex align-items-center justify-content-center"
                         style="width: 120px; height: 120px;">
                        <i class="bi bi-book-half fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h3 class="fw-bold mb-1">{{ $readingMaterial->title }}</h3>
                            <p class="text-muted mb-0">
                                <i class="bi bi-pencil me-1"></i>{{ $readingMaterial->author->name }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-4 flex-wrap">
                        <span class="badge bg-secondary">{{ $readingMaterial->category->name }}</span>
                        @if ($readingMaterial->total_pages)
                            <span class="badge bg-info">
                                <i class="bi bi-file-text me-1"></i>{{ $readingMaterial->total_pages }} pages
                            </span>
                        @endif
                        @php
                            $statusBadge = match ($readingMaterial->status->value) {
                                'Completed' => 'success',
                                'Reading' => 'warning',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge bg-{{ $statusBadge }}">{{ $readingMaterial->status->value }}</span>
                    </div>

                    @if ($readingMaterial->description)
                        <p class="text-muted mb-4">{{ $readingMaterial->description }}</p>
                    @endif

                    <div class="d-flex gap-2 mb-2">
                        <form action="{{ route('reading-sessions.start', $readingMaterial) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-play-fill me-1"></i>Start Reading
                            </button>
                        </form>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <form action="{{ route('bookmarks.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reading_material_id" value="{{ $readingMaterial->id }}">
                            <button type="submit" class="btn btn-outline-warning">
                                <i class="bi bi-bookmark me-1"></i>Bookmark
                            </button>
                        </form>

                        <a href="{{ route('reading-materials.edit', $readingMaterial) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>

                        <form action="{{ route('reading-materials.destroy', $readingMaterial) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this reading material?')">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>

                    <hr class="my-4">

                    <a href="{{ route('reading-materials.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Library
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection