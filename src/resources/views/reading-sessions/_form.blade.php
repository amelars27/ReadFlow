@php
    $session = $session ?? null;
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label for="reading_material_id" class="form-label">Reading Material</label>
        @if ($readingMaterials->count())
            <select id="reading_material_id" name="reading_material_id"
                    class="form-select @error('reading_material_id') is-invalid @enderror" required>
                <option value="">Select Reading Material</option>
                @foreach ($readingMaterials as $material)
                    <option value="{{ $material->id }}" @selected(old('reading_material_id', $session?->reading_material_id) == $material->id)>
                        {{ $material->title }}
                    </option>
                @endforeach
            </select>
            @error('reading_material_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        @else
            <div class="alert alert-warning mb-0 py-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                No reading materials available. <a href="{{ route('reading-materials.create') }}" class="alert-link">Create a reading material first</a>.
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <label for="session_date" class="form-label">Session Date</label>
        <input type="date" id="session_date" name="session_date"
               class="form-control @error('session_date') is-invalid @enderror"
               value="{{ old('session_date', $session?->session_date?->format('Y-m-d')) }}" required>
        @error('session_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="start_time" class="form-label">Start Time</label>
        <input type="time" id="start_time" name="start_time"
               class="form-control @error('start_time') is-invalid @enderror"
               value="{{ old('start_time', $session?->start_time?->format('H:i')) }}">
        @error('start_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="end_time" class="form-label">End Time</label>
        <input type="time" id="end_time" name="end_time"
               class="form-control @error('end_time') is-invalid @enderror"
               value="{{ old('end_time', $session?->end_time?->format('H:i')) }}">
        @error('end_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="duration_minutes" class="form-label">Duration (minutes)</label>
        <input type="number" id="duration_minutes" name="duration_minutes"
               class="form-control @error('duration_minutes') is-invalid @enderror"
               value="{{ old('duration_minutes', $session?->duration_minutes) }}" min="1" required>
        @error('duration_minutes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="pages_read" class="form-label">Pages Read</label>
        <input type="number" id="pages_read" name="pages_read"
               class="form-control @error('pages_read') is-invalid @enderror"
               value="{{ old('pages_read', $session?->pages_read) }}" min="0">
        @error('pages_read')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="notes" class="form-label">Notes</label>
        <textarea id="notes" name="notes" rows="4"
                  class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $session?->notes) }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
