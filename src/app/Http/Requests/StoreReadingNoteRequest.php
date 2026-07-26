<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReadingNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reading_material_id' => ['required', 'exists:reading_materials,id'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'insight' => ['required', 'string'],
            'favorite_quote' => ['nullable', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }
}
