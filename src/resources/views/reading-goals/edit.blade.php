@extends('layouts.readflow')

@section('title', 'Edit Reading Goal')

@section('header', 'Edit Reading Goal')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('reading-goals.update', $readingGoal) }}" method="POST">
                @csrf
                @method('PUT')

                @include('reading-goals._form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('reading-goals.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
