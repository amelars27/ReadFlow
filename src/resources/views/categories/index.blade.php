@extends('layouts.readflow')

@section('title', 'Categories')

@section('header', 'Categories')

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
                            placeholder="Search category..."
                            value="{{ request('search') }}">

                    </div>

                    <div class="col-md-4 d-flex align-items-center gap-3">

                        <button class="btn btn-primary">
                            Search
                        </button>

                        @if(request()->filled('search'))

                            <a href="{{ route('categories.index') }}"
                               class="reset-link small fw-semibold">

                                Reset

                            </a>

                        @endif

                    </div>

                </div>

            </form>

        </div>

        <div class="col-lg-4 text-end mt-3 mt-lg-0">

            <a href="{{ route('categories.create') }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Category

            </a>

        </div>

    </div>

    @if($categories->count())

        <div class="row g-4">

            @foreach($categories as $category)

                <div class="col-md-6 col-lg-4 col-xl-3">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body text-center d-flex flex-column">

                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width:80px;height:80px;">

                                <i class="bi bi-tags fs-2 text-secondary"></i>

                            </div>

                            <h5 class="fw-semibold mb-2">

                                {{ $category->name }}

                            </h5>

                            <p class="small text-primary mb-3">

                                <i class="bi bi-book me-1"></i>

                                {{ $category->reading_materials_count }}

                                {{ Str::plural('Reading Material', $category->reading_materials_count) }}

                            </p>

                            <p class="text-muted small mb-3"
                               style="
                                   display:-webkit-box;
                                   -webkit-line-clamp:3;
                                   -webkit-box-orient:vertical;
                                   overflow:hidden;
                               ">

                                {{ $category->description ?: 'No description available.' }}

                            </p>

                            <div class="mt-auto">

                                <small class="text-muted d-block mb-3">

                                    Created {{ $category->created_at->format('M d, Y') }}

                                </small>

                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="btn btn-outline-primary btn-sm">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('categories.destroy', $category) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Delete this category?')">

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

        @if($categories->hasPages())

            <div class="mt-4">

                {{ $categories->links() }}

            </div>

        @endif

    @else

        <div class="text-center py-5">

            <i class="bi bi-tags fs-1 text-secondary mb-3 d-block"></i>

            <h5>No categories yet</h5>

            <p class="text-muted">

                Start organizing your library by creating your first category.

            </p>

            <a href="{{ route('categories.create') }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Category

            </a>

        </div>

    @endif

@endsection
