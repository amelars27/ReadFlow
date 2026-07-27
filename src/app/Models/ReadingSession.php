<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingSession extends Model
{
    protected $fillable = [
        'user_id',
        'reading_material_id',
        'session_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'pages_read',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'start_time' => 'datetime:H:i:s',
            'end_time' => 'datetime:H:i:s',
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

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopePaused($query)
    {
        return $query->where('status', 'Paused');
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['Active', 'Paused']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }
}
