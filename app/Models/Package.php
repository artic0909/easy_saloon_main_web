<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'details',
        'original_price',
        'sale_price',
        'is_active',
        'image',
        'unique_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class);
    }
}
