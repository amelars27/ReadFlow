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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">All Reading Materials</h6>
            <a href="{{ route('reading-materials.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Add New
            </a>
        </div>
        <div class="card-body p-0">
            @if ($readingMaterials->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Source</th>
                                <th>Pages</th>
                                <th>Status</th>
                                <th class="text-center">Bookmark</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($readingMaterials as $material)
                                <tr>
                                    <td>
                                        <a href="{{ route('reading-materials.show', $material) }}" class="text-decoration-none fw-semibold">
                                            {{ $material->title }}
                                        </a>
                                    </td>
                                    <td class="text-muted">{{ $material->author->name }}</td>
                                    <td><span class="badge bg-secondary">{{ $material->category->name }}</span></td>
                                    <td><span class="badge bg-info">{{ $material->source_type->value }}</span></td>
                                    <td>{{ $material->total_pages ?? '—' }}</td>
                                    <td>
                                        @php
                                            $statusBadge = match ($material->status->value) {
                                                'Completed' => 'success',
                                                'Reading' => 'warning',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusBadge }}">{{ $material->status->value }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if (isset($bookmarks[$material->id]))
                                            <form action="{{ route('bookmarks.destroy', $bookmarks[$material->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-warning" title="Remove Bookmark">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('bookmarks.store') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="reading_material_id" value="{{ $material->id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Add Bookmark">
                                                    <i class="bi bi-bookmark"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('reading-materials.edit', $material) }}" class="btn btn-outline-primary btn-sm me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('reading-materials.destroy', $material) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this reading material?')">
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
                    <i class="bi bi-book fs-1 d-block mb-3"></i>
                    <p class="mb-0">No reading materials yet.</p>
                    <a href="{{ route('reading-materials.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-lg me-1"></i>Add Your First Reading Material
                    </a>
                </div>
            @endif
        </div>
        @if ($readingMaterials->hasPages())
            <div class="card-footer bg-transparent border-top">
                {{ $readingMaterials->links() }}
            </div>
        @endif
    </div>
@endsection
