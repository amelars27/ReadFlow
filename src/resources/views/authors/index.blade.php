@extends('layouts.readflow')

@section('title', 'Authors')

@section('header', 'Authors')

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
            <h6 class="mb-0 fw-semibold">All Authors</h6>
            <a href="{{ route('authors.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Add New
            </a>
        </div>
        <div class="card-body p-0">
            @if ($authors->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Biography</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($authors as $author)
                                <tr>
                                    <td class="fw-semibold">{{ $author->name }}</td>
                                    <td class="text-muted">{{ Str::limit($author->biography, 60) ?? '—' }}</td>
                                    <td class="text-muted">{{ $author->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('authors.edit', $author) }}" class="btn btn-outline-primary btn-sm me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('authors.destroy', $author) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this author?')">
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
                    <i class="bi bi-pencil fs-1 d-block mb-3"></i>
                    <p class="mb-0">No authors yet.</p>
                    <a href="{{ route('authors.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-lg me-1"></i>Create Your First Author
                    </a>
                </div>
            @endif
        </div>
        @if ($authors->hasPages())
            <div class="card-footer bg-transparent border-top">
                {{ $authors->links() }}
            </div>
        @endif
    </div>
@endsection
