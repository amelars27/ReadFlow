<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'genre_id' => ['nullable', 'exists:genres,id'],
            'director' => ['nullable', 'string', 'max:255'],
            'release_year' => ['nullable', 'integer', 'min:1888', 'max:2100'],
            'duration' => ['nullable', 'integer', 'min:1', 'max:60000'],
            'status' => ['required', 'string', 'in:plan_to_watch,watching,watched'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'poster' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
