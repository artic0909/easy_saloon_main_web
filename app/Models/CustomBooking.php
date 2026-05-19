<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomBooking extends Model
{
    use HasFactory;

    protected $appends = ['services'];

    protected $fillable = [
        'user_id',
        'booking_number',
        'service_ids',
        'equipment',
        'total_price',
        'discount_amount',
        'payable_amount',
        'total_duration',
        'booking_date',
        'time_slot',
        'service_type',
        'address_id',
        'staff_id',
        'status',
        'notes',
        'is_paid',
        'payment_type',
        'pay_type',
        'coupon_code',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'otp'
    ];

    protected $casts = [
        'service_ids' => 'array',
        'equipment' => 'array',
        'booking_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function getServicesAttribute()
    {
        return Service::whereIn('id', $this->service_ids)->get();
    }

    public function getTypeAttribute()
    {
        return 'Custom Package';
    }
}
