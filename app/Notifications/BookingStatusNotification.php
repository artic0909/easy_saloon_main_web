<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    use Queueable;

    protected $booking;
    protected $status;

    public function __construct($booking, $status)
    {
        $this->booking = $booking;
        $this->status = $status;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $messages = [
            'confirmed' => 'Your booking #ES-' . $this->booking->id . ' has been confirmed!',
            'completed' => 'Your service for booking #ES-' . $this->booking->id . ' is complete. We hope you enjoyed it!',
            'cancelled' => 'Your booking #ES-' . $this->booking->id . ' has been cancelled.',
            'assigned' => 'A staff member has been assigned to your booking #ES-' . $this->booking->id . '.',
        ];

        return [
            'title' => 'Booking Update',
            'message' => $messages[$this->status] ?? 'Your booking status has been updated to ' . $this->status,
            'type' => 'booking',
            'booking_id' => $this->booking->id,
            'action_url' => route('dashboard.bookings'),
        ];
    }
}
