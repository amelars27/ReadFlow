@extends('layouts.readflow')

@section('title', 'Reading Materials')

@section('header', 'Reading Materials')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0">
            <i class="bi bi-collection me-1"></i>{{ $readingMaterials->total() }} material{{ $readingMaterials->total() !== 1 ? 's' : '' }}
        </p>
        <a href="{{ route('reading-materials.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add New
        </a>
    </div>

    @if ($readingMaterials->count())
        <div class="row g-4">
            @foreach ($readingMaterials as $material)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="card border-0 shadow-sm h-100"
                         data-href="{{ route('reading-materials.show', $material) }}"
                         role="button"
                         style="cursor: pointer;">
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3">
                                <div class="bg-light rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                    <i class="bi bi-book-half fs-2 text-primary"></i>
                                </div>
                            </div>

                            <h6 class="fw-semibold text-center mb-1">{{ $material->title }}</h6>
                            <p class="small text-muted text-center mb-2">{{ $material->author->name }}</p>

                            <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
                                <span class="badge bg-secondary">{{ $material->category->name }}</span>
                                @if ($material->total_pages)
                                    <span class="badge bg-info">
                                        <i class="bi bi-file-text me-1"></i>{{ $material->total_pages }} pages
                                    </span>
                                @endif
                                @php
                                    $statusBadge = match ($material->status->value) {
                                        'Completed' => 'success',
                                        'Reading' => 'warning',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusBadge }}">{{ $material->status->value }}</span>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-center gap-2 mb-2">
                                    @if (isset($bookmarks[$material->id]))
                                        <form action="{{ route('bookmarks.destroy', $bookmarks[$material->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-warning" title="Remove Bookmark">
                                                <i class="bi bi-bookmark-fill"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('bookmarks.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="reading_material_id" value="{{ $material->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Add Bookmark">
                                                <i class="bi bi-bookmark"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('reading-sessions.start', $material) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Start Reading">
                                            <i class="bi bi-play-fill me-1"></i>Start
                                        </button>
                                    </form>
                                </div>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('reading-materials.edit', $material) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('reading-materials.destroy', $material) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this reading material?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($readingMaterials->hasPages())
            <div class="mt-4">
                {{ $readingMaterials->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-book fs-1 d-block mb-3"></i>
            <p class="mb-0">No reading materials yet.</p>
            <a href="{{ route('reading-materials.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-lg me-1"></i>Add Your First Reading Material
            </a>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.card[data-href]').forEach(function(card) {
        card.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form') || e.target.closest('input')) {
                return;
            }
            window.location.href = card.dataset.href;
        });
    });
</script>
@endpush