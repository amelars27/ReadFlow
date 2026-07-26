<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingGoal extends Model
{
    protected $fillable = [
        'user_id',
        'reading_material_id',
        'goal_type',
        'target_value',
        'current_value',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'target_value' => 'integer',
            'current_value' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function readingMaterial(): BelongsTo
    {
        return $this->belongsTo(ReadingMaterial::class);
    }
}
