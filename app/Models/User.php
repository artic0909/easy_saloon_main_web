<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable // implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone',
        'photo',
        'role',
        'is_active',
        'salon_id',
        'designation',
        'bio',
        'experience_years',
        'is_available',
        'scratch_card_claimed',
        'free_second_booking_available',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function staffProfile()
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the overall rating average for staff member from both bookings and custom bookings.
     */
    public function getStaffRatingAttribute()
    {
        $ratings = Booking::where('staff_id', $this->id)
            ->whereNotNull('rating')
            ->where('rating', '>', 0)
            ->pluck('rating')
            ->concat(
                CustomBooking::where('staff_id', $this->id)
                    ->whereNotNull('rating')
                    ->where('rating', '>', 0)
                    ->pluck('rating')
            );

        if ($ratings->isEmpty()) {
            return 0.0;
        }

        return round($ratings->average(), 1);
    }

    /**
     * Get the total ratings/reviews count for staff member.
     */
    public function getStaffRatingCountAttribute()
    {
        $bookingsCount = Booking::where('staff_id', $this->id)
            ->whereNotNull('rating')
            ->where('rating', '>', 0)
            ->count();

        $customBookingsCount = CustomBooking::where('staff_id', $this->id)
            ->whereNotNull('rating')
            ->where('rating', '>', 0)
            ->count();

        return $bookingsCount + $customBookingsCount;
    }
}
