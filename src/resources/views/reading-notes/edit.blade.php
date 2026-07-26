@extends('layouts.readflow')

@section('title', 'Edit Reading Note')

@section('header', 'Edit Reading Note')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('reading-notes.update', $readingNote) }}" method="POST">
                @csrf
                @method('PUT')

                @include('reading-notes._form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('reading-notes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
