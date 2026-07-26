@extends('layouts.readflow')

@section('title', 'Reading Notes')

@section('header', 'Reading Notes')

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
            <h6 class="mb-0 fw-semibold">All Reading Notes</h6>
            <a href="{{ route('reading-notes.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Add New
            </a>
        </div>
        <div class="card-body p-0">
            @if ($readingNotes->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Reading Material</th>
                                <th>Note Title</th>
                                <th>Rating</th>
                                <th>Created At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($readingNotes as $note)
                                <tr>
                                    <td class="fw-semibold">{{ $note->readingMaterial->title }}</td>
                                    <td>{{ $note->title }}</td>
                                    <td>
                                        @if ($note->rating)
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $note->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                            @endfor
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $note->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('reading-notes.edit', $note) }}" class="btn btn-outline-primary btn-sm me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('reading-notes.destroy', $note) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this reading note?')">
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
                    <i class="bi bi-journal-text fs-1 d-block mb-3"></i>
                    <p class="mb-0">No reading notes yet.</p>
                    <a href="{{ route('reading-notes.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-lg me-1"></i>Create Your First Reading Note
                    </a>
                </div>
            @endif
        </div>
        @if ($readingNotes->hasPages())
            <div class="card-footer bg-transparent border-top">
                {{ $readingNotes->links() }}
            </div>
        @endif
    </div>
@endsection
