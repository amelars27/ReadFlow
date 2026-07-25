@extends('layouts.readflow')

@section('title', 'Edit Reading Session')

@section('header', 'Edit Reading Session')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('reading-sessions.update', $readingSession) }}" method="POST">
                @csrf
                @method('PUT')

                @include('reading-sessions._form', ['session' => $readingSession])

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('reading-sessions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary" @disabled(!$readingMaterials->count())>
                        <i class="bi bi-check-lg me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
