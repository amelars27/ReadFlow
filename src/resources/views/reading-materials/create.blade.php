@extends('layouts.readflow')

@section('title', 'Create Reading Material')

@section('header', 'Create Reading Material')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('reading-materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label">Cover Image</label>
                        <div class="bg-light rounded-3 d-flex flex-column align-items-center justify-content-center p-4 border">
                            <i class="bi bi-book-half fs-1 text-primary mb-2"></i>
                            <p class="small text-muted text-center mb-1">No cover image selected</p>
                            <p class="small text-muted text-center mb-0">
                                <i class="bi bi-info-circle me-1"></i>Cover image can be uploaded after creation from the book detail page.
                            </p>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="author_id" class="form-label">Author</label>
                        <div class="d-flex gap-2">
                            <select id="author_id" name="author_id"
                                    class="form-select @error('author_id') is-invalid @enderror" required>
                                <option value="">Select Author</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}" @selected(old('author_id') == $author->id)>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('authors.create') }}" target="_blank" class="btn btn-outline-secondary flex-shrink-0">+ New</a>
                        </div>
                        @error('author_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Category</label>
                        @if ($categories->count())
                            <div class="d-flex gap-2">
                                <select id="category_id" name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <a href="{{ route('categories.create') }}" target="_blank" class="btn btn-outline-secondary flex-shrink-0">+ New</a>
                            </div>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        @else
                            <div class="alert alert-warning mb-0 py-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                No categories available. <a href="{{ route('categories.create') }}" class="alert-link">Create a category first</a>.
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="total_pages" class="form-label">Total Pages</label>
                        <input type="number" id="total_pages" name="total_pages"
                               class="form-control @error('total_pages') is-invalid @enderror"
                               value="{{ old('total_pages') }}" min="1">
                        @error('total_pages')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status"
                                class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status') == $status->value)>
                                    {{ $status->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="source_type" class="form-label">Source Type</label>
                        <select id="source_type" name="source_type"
                                class="form-select @error('source_type') is-invalid @enderror" required>
                            <option value="">Select Source Type</option>
                            @foreach ($sourceTypes as $type)
                                <option value="{{ $type->value }}" @selected(old('source_type') == $type->value)>
                                    {{ $type->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('source_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="source_url" class="form-label">Source URL</label>
                        <input type="url" id="source_url" name="source_url"
                               class="form-control @error('source_url') is-invalid @enderror"
                               value="{{ old('source_url') }}">
                        @error('source_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('reading-materials.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary" @disabled(!$categories->count())>
                        <i class="bi bi-check-lg me-1"></i>Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
