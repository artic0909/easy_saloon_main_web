<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
