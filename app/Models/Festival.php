<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Festival extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'start_date',
        'end_date',
        'description',
        'max_capacity',
        'ticket_price',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'max_capacity' => 'integer',
            'ticket_price' => 'decimal:2',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function busTrips(): HasMany
    {
        return $this->hasMany(BusTrip::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function registrationCount(): int
    {
        return $this->registrations()->count();
    }

    public function confirmedBookingsCount(): int
    {
        return $this->bookings()->where('status', 'confirmed')->count();
    }

    public function availableSpots(): int
    {
        return max(0, $this->max_capacity - $this->confirmedBookingsCount());
    }

    public function isFull(): bool
    {
        return $this->availableSpots() === 0;
    }

    public function hasBookingFrom(User $user): bool
    {
        return $this->bookings()
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->exists();
    }
}
