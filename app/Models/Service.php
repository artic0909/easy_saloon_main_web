<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'details',
        'what_included',
        'original_price',
        'sale_price',
        'duration_minutes',
        'is_active',
        'images',
        'unique_id',
    ];

    protected $casts = [
        'what_included' => 'array',
        'images' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class);
    }
}
