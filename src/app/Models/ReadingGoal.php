<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingGoal extends Model
{
    protected $fillable = [
        'user_id',
        'daily_target_minutes',
        'weekly_target_minutes',
        'yearly_target_books',
    ];

    protected function casts(): array
    {
        return [
            'daily_target_minutes' => 'integer',
            'weekly_target_minutes' => 'integer',
            'yearly_target_books' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
