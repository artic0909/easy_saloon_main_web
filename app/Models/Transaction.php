<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'custom_booking_id',
        'transaction_id',
        'amount',
        'payment_mode',
        'status',
        'type',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customBooking()
    {
        return $this->belongsTo(CustomBooking::class, 'custom_booking_id');
    }
}
