<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PointsTransaction;
use App\Models\User;

class PointsService
{
    public function awardForBooking(User $user, Booking $booking): void
    {
        $points = config('fts.points_per_booking');

        PointsTransaction::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'type' => 'earned',
            'amount' => $points,
            'description' => 'Punten verdiend bij boeking',
        ]);

        $user->increment('points_balance', $points);
    }
}
