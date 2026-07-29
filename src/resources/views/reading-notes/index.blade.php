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

   <div class="card border-0 shadow h-100 rounded-4">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">All Reading Notes</h6>
            <a href="{{ route('reading-notes.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Add New
            </a>
        </div>
        <div class="card-body p-4">

    @if ($readingNotes->count())

        <div class="row g-4">

            @foreach ($readingNotes as $note)

                <div class="col-lg-4 col-md-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body d-flex flex-column">

                            <div class="mb-3">

                                <h5 class="fw-bold text-dark mb-1">
                                    {{ $note->readingMaterial->title }}
                                </h5>

                                <small class="text-muted">
                                    {{ $note->readingMaterial->author?->name ?? 'Unknown Author' }}
                                </small>

                            </div>

                           <span class="badge rounded-pill bg-primary-subtle text-primary mb-3 align-self-start px-3 py-2">
                                {{ $note->readingMaterial->category?->name ?? 'Uncategorized' }}
                            </span>

                            <h6 class="fw-semibold text-primary">
                                💬 Favorite Quote
                            </h6>

                            <blockquote class="border-start border-4 border-primary ps-3 py-2 fst-italic text-secondary small mb-4 bg-light rounded">
                                {{ \Illuminate\Support\Str::limit($note->favorite_quote, 120) }}

                            </blockquote>

                            <div class="mt-auto">

                                <div class="text-muted small mb-3">

                                    <i class="bi bi-calendar3"></i>

                                    {{ $note->created_at->format('d M Y') }}

                                </div>

                                <div class="d-flex gap-2">

                                    <a href="{{ route('reading-notes.show',$note) }}"
                                        class="btn btn-primary btn-sm flex-fill">

                                        <i class="bi bi-eye"></i>

                                        View

                                    </a>

                                    <a href="{{ route('reading-notes.edit',$note) }}"
                                        class="btn btn-outline-primary btn-sm">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('reading-notes.destroy',$note) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            onclick="return confirm('Delete this note?')"
                                            class="btn btn-outline-danger btn-sm">

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

    @else

        <div class="text-center py-5">

            <i class="bi bi-journal-richtext display-3 text-primary"></i>

            <h4 class="mt-3">

                No Reading Notes Yet

            </h4>

            <p class="text-muted">

                Capture your favorite quotes, summaries,
                and insights from every book you read.

            </p>

            <a href="{{ route('reading-notes.create') }}"
                class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>

                Create First Note

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

