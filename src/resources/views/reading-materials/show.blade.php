@extends('layouts.readflow')

@section('title', $readingMaterial->title . ' — Reading Materials')

@section('header', $readingMaterial->title)

@section('content')
    <div class="row g-4">
        <div class="col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <form action="{{ route('reading-materials.cover', $readingMaterial) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="cover_image" id="cover_image" hidden accept="image/*">
                        @if ($readingMaterial->cover_image)
                            <div id="cover-placeholder"
                                 role="button"
                                 style="cursor: pointer; min-height: 260px;"
                                 class="rounded-3 d-flex align-items-center justify-content-center overflow-hidden transition-shadow">
                                <img src="{{ Storage::url($readingMaterial->cover_image) }}"
                                     alt="{{ $readingMaterial->title }} cover"
                                     class="img-fluid rounded-3"
                                     style="max-height: 260px; object-fit: contain;">
                            </div>
                            <p class="small text-muted mt-2 mb-0">
                                <i class="bi bi-camera me-1"></i>Click to change cover
                            </p>
                        @else
                            <div id="cover-placeholder"
                                 role="button"
                                 style="cursor: pointer; min-height: 260px;"
                                 class="bg-light rounded-3 d-flex flex-column align-items-center justify-content-center transition-shadow">
                                <i class="bi bi-book-half fs-1 text-primary mb-2"></i>
                                <span class="small text-muted">Click to Upload Cover</span>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">{{ $readingMaterial->title }}</h3>

                    <div class="mb-3">
                        <p class="mb-2">
                            <i class="bi bi-pencil text-muted me-2"></i>
                            <span>{{ $readingMaterial->author->name }}</span>
                        </p>
                            @if($readingMaterial->rating)
        <div class="mb-2">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $readingMaterial->rating)
                    <i class="bi bi-star-fill text-warning"></i>
                @else
                    <i class="bi bi-star text-warning"></i>
                @endif
            @endfor
            <span class="ms-2 text-muted">{{ $readingMaterial->rating }}/5</span>
        </div>
    @endif

    <div class="d-flex gap-2 flex-wrap"> 
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-secondary">
                                <i class="bi bi-folder me-1"></i>{{ $readingMaterial->category->name }}
                            </span>
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
                            <span class="badge bg-{{ $statusBadge }}">
                                <i class="bi bi-tag me-1"></i>{{ $readingMaterial->status->value }}
                            </span>
                        </div>
                    </div>

                    @if ($readingMaterial->description)
                        <hr class="my-3">
                        <p class="text-muted mb-0">{{ $readingMaterial->description }}</p>
                    @endif

                    <hr class="my-3">

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('reading-goals.create', ['reading_material_id' => $readingMaterial->id]) }}" class="btn btn-success">
                            <i class="bi bi-bullseye me-1"></i>Set Reading Goal
                        </a>
                        @if ($bookmark)
    <form action="{{ route('bookmarks.destroy', $bookmark) }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-warning">
            <i class="bi bi-bookmark-fill me-1"></i>Bookmarked
        </button>
    </form>
@else
    <form action="{{ route('bookmarks.store') }}" method="POST">
        @csrf
        <input type="hidden" name="reading_material_id" value="{{ $readingMaterial->id }}">

        <button type="submit" class="btn btn-outline-warning">
            <i class="bi bi-bookmark me-1"></i>Bookmark
        </button>
    </form>
@endif
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

                    <hr class="my-3">

                    <a href="{{ route('reading-materials.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Library
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const placeholder = document.getElementById('cover-placeholder');
    const fileInput = document.getElementById('cover_image');

    if (placeholder && fileInput) {
        placeholder.addEventListener('mouseenter', function() {
            this.classList.add('shadow');
        });
        placeholder.addEventListener('mouseleave', function() {
            this.classList.remove('shadow');
        });
        placeholder.addEventListener('click', function() {
            fileInput.click();
        });
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                this.form.submit();
            }
        });
    }
</script>
@endpush