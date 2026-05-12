<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipment';
    protected $fillable = ['sub_category_id', 'name'];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
