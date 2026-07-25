<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = ['name', 'biography'];

    public function readingMaterials(): HasMany
    {
        return $this->hasMany(ReadingMaterial::class);
    }
}
