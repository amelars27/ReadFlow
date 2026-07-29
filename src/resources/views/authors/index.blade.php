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

    <div class="row align-items-end mb-4">

        <div class="col-lg-8">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-md-8">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search author..."
                            value="{{ request('search') }}">

                    </div>

                    <div class="col-md-4 d-flex align-items-center gap-3">

                        <button class="btn btn-primary">
                            Search
                        </button>

                        @if(request()->filled('search'))

                            <a href="{{ route('authors.index') }}"
                               class="reset-link small fw-semibold">

                                Reset

                            </a>

                        @endif

                    </div>

                </div>

            </form>

        </div>

        <div class="col-lg-4 text-end mt-3 mt-lg-0">

            <a href="{{ route('authors.create') }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Author

            </a>

        </div>

    </div>

    @if($authors->count())

        <div class="row g-4">

            @foreach($authors as $author)

                <div class="col-md-6 col-lg-4 col-xl-3">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body text-center d-flex flex-column">

                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width:80px;height:80px;">

                                <i class="bi bi-person-fill fs-1 text-secondary"></i>

                            </div>

                            <h5 class="fw-semibold mb-1">

                                {{ $author->name }}

                            </h5>

                            <p class="small text-primary mb-3">

                                <i class="bi bi-book me-1"></i>

                                {{ $author->reading_materials_count }}

                                {{ Str::plural('Reading Material', $author->reading_materials_count) }}

                            </p>

                            <p class="text-muted small mb-3"
                               style="
                                    display:-webkit-box;
                                    -webkit-line-clamp:3;
                                    -webkit-box-orient:vertical;
                                    overflow:hidden;
                               ">

                                {{ $author->biography ?: 'No biography available.' }}

                            </p>

                            <div class="mt-auto">

                                <small class="text-muted d-block mb-3">

                                    Created
                                    {{ $author->created_at->format('M d, Y') }}

                                </small>

                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('authors.edit',$author) }}"
                                       class="btn btn-outline-primary btn-sm">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('authors.destroy',$author) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Delete this author?')">

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

        @if($authors->hasPages())

            <div class="mt-4">

                {{ $authors->links() }}

            </div>

        @endif

    @else

        <div class="text-center py-5">

            <i class="bi bi-person fs-1 text-secondary mb-3 d-block"></i>

            <h5>No authors yet</h5>

            <p class="text-muted">

                Start building your library by adding your first author.

            </p>

            <a href="{{ route('authors.create') }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Author

            </a>

        </div>

    @endif

@endsection
