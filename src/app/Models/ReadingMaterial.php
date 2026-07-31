<?php

namespace App\Models;

use App\Enums\ReadingStatus;
use App\Enums\SourceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReadingMaterial extends Model
{
    protected $fillable = [
    'user_id',
    'category_id',
    'author_id',
    'title',
    'source_type',
    'source_url',
    'description',
    'total_pages',
    'status',
    'cover_image',
    'rating',
];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'source_type' => SourceType::class,
            'status' => ReadingStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function readingSessions(): HasMany
    {
        return $this->hasMany(ReadingSession::class);
    }

    public function readingNotes(): HasMany
    {
        return $this->hasMany(ReadingNote::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function readingGoals(): HasMany
    {
        return $this->hasMany(ReadingGoal::class);
    }
}
