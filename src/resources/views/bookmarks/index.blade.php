@extends('layouts.readflow')

@section('title', 'Bookmarks')

@section('header', 'Bookmarks')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-bookmark-fill me-2 text-warning"></i>Bookmarks
            </h5>
            <p class="text-muted small mb-0 mt-1">
                Save your bookmarked reading materials for quick access.
            </p>
        </div>

        <div class="card-body">
            @if($bookmarks->count())

                <div class="row">

                    @foreach($bookmarks as $bookmark)

                        <div class="col-md-6 col-lg-4 mb-4">

                            <div class="card h-100">

                                <div class="row g-0 h-100">

                                    @if(optional($bookmark->readingMaterial)->cover_image)
    <div class="col-md-4">
        <img src="{{ Storage::url($bookmark->readingMaterial->cover_image) }}"
             alt="{{ $bookmark->readingMaterial->title }}"
             class="img-fluid rounded-start h-100"
             style="object-fit: cover;">
    </div>
@endif

<div class="@if(optional($bookmark->readingMaterial)->cover_image) col-md-8 @else col-12 @endif">
    <div class="card-body d-flex flex-column h-100">

        <h5 class="card-title">
            {{ optional($bookmark->readingMaterial)->title ?? 'Unknown Material' }}
        </h5>

                                            <p class="text-muted mb-1">
                                                <i class="bi bi-pencil me-1"></i>
                                                {{ optional(optional($bookmark->readingMaterial)->author)->name ?? '-' }}
                                            </p>

                                            <p class="text-muted mb-2">
                                                <i class="bi bi-tag me-1"></i>
                                                {{ optional(optional($bookmark->readingMaterial)->category)->name ?? '-' }}
                                            </p>

                                            @if($bookmark->created_at)
                                                <small class="text-muted mt-auto">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Bookmarked on {{ $bookmark->created_at->format('M d, Y') }}
                                                </small>
                                            @endif

                                            <div class="d-flex gap-2 mt-3">
                                                <a href="{{ route('reading-materials.show', $bookmark->readingMaterial) }}"
                                                   class="btn btn-primary btn-sm flex-fill">
                                                    <i class="bi bi-eye me-1"></i>View Material
                                                </a>

                                                <form action="{{ route('bookmarks.destroy', $bookmark) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-warning btn-sm">
                                                        <i class="bi bi-bookmark-dash me-1"></i>Remove
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{ $bookmarks->links() }}

            @else

                <div class="text-center py-5">

                    <h5><i class="bi bi-bookmark text-warning me-2"></i>No bookmarks yet</h5>

                    <p class="text-muted">
                        Browse your reading materials and save them as bookmarks for quick access.
                    </p>

                    <a href="{{ route('reading-materials.index') }}" class="btn btn-primary">
                        Browse Reading Materials
                    </a>

                </div>

            @endif
        </div>
    </div>
@endsection