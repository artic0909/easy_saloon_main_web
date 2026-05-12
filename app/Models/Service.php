<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'name',
        'slug',
        'details',
        'what_included',
        'original_price',
        'sale_price',
        'duration_minutes',
        'is_active',
        'image',
    ];

    protected $casts = [
        'what_included' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class);
    }
}
