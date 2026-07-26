<?php

namespace App\Http\Requests;

use App\Models\Bookmark;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookmarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reading_material_id' => [
                'required',
                'exists:reading_materials,id',
                function ($attribute, $value, $fail) {
                    $exists = Bookmark::where('user_id', auth()->id())
                        ->where('reading_material_id', $value)
                        ->exists();

                    if ($exists) {
                        $fail('This reading material is already bookmarked.');
                    }
                },
            ],
        ];
    }
}
