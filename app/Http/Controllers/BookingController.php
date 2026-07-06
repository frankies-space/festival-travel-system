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

        DB::transaction(function () use ($user, $festival, $pointsService) {
            $booking = Booking::create([
                'user_id' => $user->id,
                'festival_id' => $festival->id,
                'status' => 'confirmed',
                'price' => $festival->ticket_price,
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
            ->with('status', 'booking-confirmed');
    }
}
