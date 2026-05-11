<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CouponNotification extends Notification
{
    use Queueable;

    protected $coupon;

    public function __construct($coupon)
    {
        $this->coupon = $coupon;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'New Offer: ' . $this->coupon->code,
            'message' => 'Use code ' . $this->coupon->code . ' to get ' . ($this->coupon->discount_type == 'fixed' ? '₹' : '') . $this->coupon->discount_value . ($this->coupon->discount_type == 'percentage' ? '% Off' : ' Off') . ' on your next booking!',
            'type' => 'coupon',
            'coupon_id' => $this->coupon->id,
            'code' => $this->coupon->code,
            'action_url' => route('dashboard.bookings'),
        ];
    }
}
