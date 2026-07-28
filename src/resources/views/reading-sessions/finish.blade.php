@extends('layouts.readflow')

@section('title', 'Finish Reading Session')

@section('header', 'Finish Reading')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @php
                        $material = $readingSession->readingGoal?->readingMaterial;
                    @endphp

                    <div class="text-center mb-4">
                        <h5 class="fw-semibold">{{ $material?->title ?? 'Unknown Book' }}</h5>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-clock me-1"></i>
                            @php
                                $mins = intdiv($elapsed, 60);
                                $secs = $elapsed % 60;
                            @endphp
                            {{ intdiv($mins, 60) }}h {{ $mins % 60 }}m {{ $secs }}s
                        </p>
                    </div>

                    <hr class="my-3">

                    <form action="{{ route('reading-sessions.finish', $readingSession) }}" method="POST">
                        @csrf

                        <input type="hidden" name="elapsed_seconds" value="{{ $elapsed }}">

                        <div class="mb-3">
                            <label for="start_page" class="form-label fw-semibold">
                                Start Page <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   id="start_page"
                                   name="start_page"
                                   class="form-control @error('start_page') is-invalid @enderror"
                                   value="{{ old('start_page') }}"
                                   min="1"
                                   autofocus>
                            @error('start_page')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_page" class="form-label fw-semibold">
                                End Page <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   id="end_page"
                                   name="end_page"
                                   class="form-control @error('end_page') is-invalid @enderror"
                                   value="{{ old('end_page') }}"
                                   min="1">
                            @error('end_page')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">Notes</label>
                            <textarea id="notes"
                                      name="notes"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('reading-sessions.index') }}" class="btn btn-outline-secondary flex-fill">
                                <i class="bi bi-x-lg me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-check-lg me-1"></i>Complete Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection