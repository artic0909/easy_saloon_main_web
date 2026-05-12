<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'staff_id',
        'salon_id',
        'booking_number',
        'booking_date',
        'time_slot',
        'service_type',
        'total_price',
        'discount_amount',
        'payable_amount',
        'status',
        'is_paid',
        'payment_type',
        'address_id',
        'cancellation_reason',
        'equipment'
    ];

    protected $casts = [
        'equipment' => 'array',
        'booking_date' => 'date',
        'is_paid' => 'boolean'
    ];

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function getTypeAttribute()
    {
        return $this->items()->where('item_type', 'package')->exists() ? 'Package' : 'Service';
    }
}
