@extends('layouts.readflow')

@section('title', 'Create Reading Note')

@section('header', 'Create Reading Note')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('reading-notes.store') }}" method="POST">
                @csrf

                @include('reading-notes._form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('reading-notes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
