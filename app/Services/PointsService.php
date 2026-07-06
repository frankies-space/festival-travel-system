<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PointsTransaction;
use App\Models\User;
use InvalidArgumentException;

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

    public function redeemForDiscount(User $user, int $points): float
    {
        $minPoints = config('fts.min_redeem_discount_points');

        if ($points < $minPoints) {
            throw new InvalidArgumentException("Minimaal {$minPoints} punten nodig voor korting.");
        }

        if ($user->points_balance < $points) {
            throw new InvalidArgumentException('Onvoldoende punten.');
        }

        $discountAmount = $points / config('fts.points_per_euro_discount');

        PointsTransaction::create([
            'user_id' => $user->id,
            'type' => 'redeemed_discount',
            'amount' => -$points,
            'description' => "Korting van €{$discountAmount} ingewisseld",
        ]);

        $user->decrement('points_balance', $points);
        $user->increment('available_discount', $discountAmount);

        return $discountAmount;
    }

    public function redeemForVip(User $user): void
    {
        $cost = config('fts.vip_redeem_cost');

        if ($user->vip_until?->isFuture()) {
            throw new InvalidArgumentException('Je hebt al VIP-toegang.');
        }

        if ($user->points_balance >= config('fts.vip_threshold')) {
            throw new InvalidArgumentException('Je hebt al VIP-status via je puntensaldo.');
        }

        if ($user->points_balance < $cost) {
            throw new InvalidArgumentException('Onvoldoende punten voor VIP-toegang.');
        }

        PointsTransaction::create([
            'user_id' => $user->id,
            'type' => 'redeemed_vip',
            'amount' => -$cost,
            'description' => 'VIP-toegang ingewisseld',
        ]);

        $user->decrement('points_balance', $cost);
        $user->update([
            'vip_until' => now()->addDays(config('fts.vip_redeem_days')),
        ]);
    }

    public function applyDiscountToBooking(User $user, float $ticketPrice): array
    {
        $discountUsed = min((float) $user->available_discount, $ticketPrice);
        $finalPrice = $ticketPrice - $discountUsed;

        if ($discountUsed > 0) {
            $user->decrement('available_discount', $discountUsed);
        }

        return [
            'final_price' => $finalPrice,
            'discount_used' => $discountUsed,
        ];
    }
}
