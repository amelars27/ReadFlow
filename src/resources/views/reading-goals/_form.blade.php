@php
    $isEdit = isset($readingGoal);
    $goalTypes = ['books', 'pages', 'minutes'];
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label for="reading_material_id" class="form-label">Reading Material</label>
        <select id="reading_material_id" name="reading_material_id"
                class="form-select @error('reading_material_id') is-invalid @enderror" required>
            <option value="">Select Reading Material</option>
            @foreach ($readingMaterials as $material)
                <option value="{{ $material->id }}" @selected(old('reading_material_id', $isEdit ? $readingGoal->reading_material_id : null) == $material->id)>
                    {{ $material->title }}
                </option>
            @endforeach
        </select>
        @error('reading_material_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="goal_type" class="form-label">Goal Type</label>
        <select id="goal_type" name="goal_type"
                class="form-select @error('goal_type') is-invalid @enderror" required>
            <option value="">Select Goal Type</option>
            @foreach ($goalTypes as $type)
                <option value="{{ $type }}" @selected(old('goal_type', $isEdit ? $readingGoal->goal_type : null) == $type)>
                    {{ ucfirst($type) }}
                </option>
            @endforeach
        </select>
        @error('goal_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="target_value" class="form-label">Target Value</label>
        <input type="number" id="target_value" name="target_value"
               class="form-control @error('target_value') is-invalid @enderror"
               value="{{ old('target_value', $isEdit ? $readingGoal->target_value : '') }}" min="1" required>
        @error('target_value')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if ($isEdit)
        <div class="col-md-6">
            <label for="current_value" class="form-label">Current Value</label>
            <input type="number" id="current_value" name="current_value"
                   class="form-control @error('current_value') is-invalid @enderror"
                   value="{{ old('current_value', $readingGoal->current_value) }}" min="0">
            @error('current_value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif

    <div class="col-md-6">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" id="start_date" name="start_date"
               class="form-control @error('start_date') is-invalid @enderror"
               value="{{ old('start_date', $isEdit ? $readingGoal->start_date->format('Y-m-d') : '') }}" required>
        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" id="end_date" name="end_date"
               class="form-control @error('end_date') is-invalid @enderror"
               value="{{ old('end_date', $isEdit ? $readingGoal->end_date->format('Y-m-d') : '') }}" required>
        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
