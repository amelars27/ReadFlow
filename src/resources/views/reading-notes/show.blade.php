@extends('layouts.readflow')

@section('title', 'Reading Note')

@section('header', 'Reading Note')

@section('content')

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="row">

            <div class="col-md-3 text-center">

                @if($readingNote->readingMaterial->cover_image)

                    <img src="{{ Storage::url($readingNote->readingMaterial->cover_image) }}"
                        class="img-fluid rounded shadow">

                @else

                    <div class="bg-light rounded p-5">

                        <i class="bi bi-book fs-1 text-secondary"></i>

                    </div>

                @endif

            </div>

            <div class="col-md-9">

                <h2 class="fw-bold">

                    {{ $readingNote->readingMaterial->title }}

                </h2>

                <p class="text-muted">

                    {{ $readingNote->readingMaterial->author?->name ?? '-' }}

                </p>

                <span class="badge bg-primary">

                    {{ $readingNote->readingMaterial->category?->name ?? '-' }}

                </span>

                <hr>

                <h5 class="text-primary">

                    💬 Favorite Quote

                </h5>

                <blockquote class="border-start border-4 border-primary ps-3 fst-italic">

                    "{{ $readingNote->favorite_quote }}"

                </blockquote>

                <hr>

                <h5>

                    📖 Summary

                </h5>

                <p>

                    {{ $readingNote->summary }}

                </p>

                <hr>

                <h5>

                    💡 Personal Insight

                </h5>

                <p>

                    {{ $readingNote->insight }}

                </p>

                <hr>

                <small class="text-muted">

                    Created :
                    {{ $readingNote->created_at->format('d M Y') }}

                </small>

                <div class="mt-4">

                    <a href="{{ route('reading-notes.index') }}"
                        class="btn btn-secondary">

                        Back

                    </a>

                    <a href="{{ route('reading-notes.edit',$readingNote) }}"
                        class="btn btn-primary">

                        Edit

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection