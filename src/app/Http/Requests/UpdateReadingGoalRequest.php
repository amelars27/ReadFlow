<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReadingGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reading_material_id' => ['required', 'exists:reading_materials,id'],
            'goal_type' => ['required', 'string', 'in:books,pages,minutes'],
            'target_value' => ['required', 'integer', 'min:1'],
            'current_value' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }
}
