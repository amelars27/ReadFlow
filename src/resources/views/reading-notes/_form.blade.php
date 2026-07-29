@php
    $isEdit = isset($readingNote);
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label for="reading_material_id" class="form-label">Reading Material</label>
        <select id="reading_material_id" name="reading_material_id"
                class="form-select @error('reading_material_id') is-invalid @enderror" required>
            <option value="">Select Reading Material</option>
            @foreach ($readingMaterials as $material)
                <option value="{{ $material->id }}" @selected(old('reading_material_id', $isEdit ? $readingNote->reading_material_id : null) == $material->id)>
                    {{ $material->title }}
                </option>
            @endforeach
        </select>
        @error('reading_material_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" id="title" name="title"
               class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $isEdit ? $readingNote->title : '') }}" required>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="summary" class="form-label">Summary</label>
        <textarea id="summary" name="summary" rows="4"
                  class="form-control @error('summary') is-invalid @enderror" required>{{ old('summary', $isEdit ? $readingNote->summary : '') }}</textarea>
        @error('summary')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="insight" class="form-label">Insight</label>
        <textarea id="insight" name="insight" rows="4"
                  class="form-control @error('insight') is-invalid @enderror" required>{{ old('insight', $isEdit ? $readingNote->insight : '') }}</textarea>
        @error('insight')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="favorite_quote" class="form-label">Favorite Quote</label>
        <textarea id="favorite_quote" name="favorite_quote" rows="3"
                  class="form-control @error('favorite_quote') is-invalid @enderror">{{ old('favorite_quote', $isEdit ? $readingNote->favorite_quote : '') }}</textarea>
        @error('favorite_quote')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    
