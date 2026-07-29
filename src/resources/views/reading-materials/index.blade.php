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
                        <div class="card-body d-flex flex-column p-0">
                            <div class="d-flex flex-column align-items-center px-3 pt-3 pb-2">
                                @if ($material->cover_image)
                                    <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center mb-3 w-100" style="height: 180px;">
                                        <img src="{{ Storage::url($material->cover_image) }}"
                                             alt="{{ $material->title }} cover"
                                             class="img-fluid h-100 w-100"
                                             style="object-fit: cover;">
                                    </div>
                                @else
                                    <div class="bg-light rounded-3 d-flex flex-column align-items-center justify-content-center mb-3 w-100" style="height: 180px;">
                                        <i class="bi bi-book-half fs-1 text-primary mb-1"></i>
                                        <span class="small text-muted">No Cover</span>
                                    </div>
                                @endif

                                <h6 class="fw-semibold text-center mb-1 text-truncate w-100" title="{{ $material->title }}">
    {{ $material->title }}
</h6>

<p class="small text-muted text-center mb-2 text-truncate w-100">
    {{ $material->author->name }}
</p>

@if ($material->rating)
    <div class="text-center mb-2">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $material->rating)
                <i class="bi bi-star-fill text-warning"></i>
            @else
                <i class="bi bi-star text-warning"></i>
            @endif
        @endfor

        <span class="small text-muted ms-1">
            {{ $material->rating }}/5
        </span>
    </div>
@endif

@if ($material->description)
    <p class="small text-muted text-center mb-2 px-1"
       style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
        {{ $material->description }}
    </p>
@endif

<div class="d-flex justify-content-center gap-1 mb-2 flex-wrap">
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
                            </div>

                            <div class="border-top px-3 py-2 mt-auto">
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
                                    <a href="{{ route('reading-materials.show', $material) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('reading-materials.edit', $material) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('reading-materials.destroy', $material) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Delete"
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