@extends('layouts.readflow')

@section('title', 'Reading Queue')

@section('header', 'Reading Queue')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Reading Queue</h5>
        </div>

        <div class="card-body">
            @if($bookmarks->count())

                <div class="row">

                    @foreach($bookmarks as $bookmark)

                        <div class="col-md-6 col-lg-4 mb-4">

                            <div class="card h-100">

                                <div class="card-body">

                                    <h5>
                                        {{ optional($bookmark->readingMaterial)->title ?? 'Unknown Material' }}
                                    </h5>

                                    <p class="text-muted mb-1">
                                        Author :
                                        {{ optional(optional($bookmark->readingMaterial)->author)->name ?? '-' }}
                                    </p>

                                    <p class="text-muted mb-2">
                                        Category :
                                        {{ optional(optional($bookmark->readingMaterial)->category)->name ?? '-' }}
                                    </p>

                                    @if($bookmark->created_at)
                                        <small class="text-muted">
                                            Added :
                                            {{ $bookmark->created_at->format('M d, Y') }}
                                        </small>
                                    @endif

                                </div>

                                <div class="card-footer bg-white d-flex gap-2">

                                    <button class="btn btn-primary btn-sm flex-fill" disabled>
                                        Reading Timer (Coming Soon)
                                    </button>

                                    <form action="{{ route('bookmarks.destroy',$bookmark) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-outline-danger btn-sm">
                                            Remove
                                        </button>
                                    </form>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{ $bookmarks->links() }}

            @else

                <div class="text-center py-5">

                    <h5>Your reading queue is empty.</h5>

                    <p class="text-muted">
                        Bookmark your prioritized reading materials so they are ready for your next reading session.
                    </p>

                    <a href="{{ route('reading-materials.index') }}" class="btn btn-primary">
                        Browse Reading Materials
                    </a>

                </div>

            @endif
        </div>
    </div>
@endsection