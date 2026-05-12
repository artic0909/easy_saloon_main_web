<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingConfirmation extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number,
            'title' => 'Booking Confirmed!',
            'message' => "Your booking #{$this->booking->booking_number} for " . ($this->booking->booking_date) . " has been successfully placed.",
            'type' => 'booking',
            'icon' => 'calendar-check'
        ];
    }
}
