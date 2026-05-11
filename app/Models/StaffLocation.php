<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffLocation extends Model
{
    protected $fillable = [
        'staff_id',
        'booking_id',
        'latitude',
        'longitude',
        'captured_at'
    ];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
