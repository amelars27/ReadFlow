<?php

namespace App\Http\Requests;

use App\Enums\ReadingStatus;
use App\Enums\SourceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreReadingMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author_id' => ['required', 'exists:authors,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'source_type' => ['required', new Enum(SourceType::class)],
            'status' => ['required', new Enum(ReadingStatus::class)],
            'total_pages' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:5000'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'cover_image' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
