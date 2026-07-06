<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Festival;
use App\Models\Registration;
use App\Services\PointsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, Festival $festival, PointsService $pointsService): RedirectResponse
    {
        $user = $request->user();
        $discountApplied = 0;

        DB::transaction(function () use ($user, $festival, $pointsService, &$discountApplied) {
            $pricing = $pointsService->applyDiscountToBooking($user, (float) $festival->ticket_price);
            $discountApplied = $pricing['discount_used'];

            $booking = Booking::create([
                'user_id' => $user->id,
                'festival_id' => $festival->id,
                'status' => 'confirmed',
                'price' => $pricing['final_price'],
                'booked_at' => now(),
            ]);

            Registration::create([
                'user_id' => $user->id,
                'festival_id' => $festival->id,
                'status' => 'confirmed',
                'registered_at' => now(),
            ]);

            $pointsService->awardForBooking($user, $booking);
        });

        return redirect()
            ->route('profile.edit')
            ->with('status', 'booking-confirmed')
            ->with('discount_applied', $discountApplied);
    }
}
