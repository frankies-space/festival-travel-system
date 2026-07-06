<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BusTrip;
use App\Models\Festival;
use App\Models\Registration;

class BusPlanningService
{
    public function handleNewRegistration(Festival $festival): void
    {
        $registrationCount = $festival->registrations()->count();

        if ($registrationCount <= config('fts.bus_trigger_threshold')) {
            return;
        }

        $busTrip = $festival->busTrips()
            ->where('status', 'planned')
            ->first();

        if (! $busTrip) {
            $busTrip = BusTrip::create([
                'festival_id' => $festival->id,
                'departure_date' => $festival->start_date,
                'departure_location' => 'Amsterdam Centraal',
                'max_passengers' => config('fts.bus_max_passengers'),
                'status' => 'planned',
            ]);
        }

        $this->assignPassengersWithVipPriority($festival, $busTrip);
    }

    public function assignPassengersWithVipPriority(Festival $festival, BusTrip $busTrip): void
    {
        $maxPassengers = $busTrip->max_passengers;

        $registrations = $festival->registrations()
            ->with('user')
            ->get()
            ->sort(function (Registration $a, Registration $b) {
                $aVip = $a->user->isVip();
                $bVip = $b->user->isVip();

                if ($aVip !== $bVip) {
                    return $aVip ? -1 : 1;
                }

                return $a->registered_at <=> $b->registered_at;
            })
            ->values();

        foreach ($registrations as $index => $registration) {
            $booking = Booking::where('user_id', $registration->user_id)
                ->where('festival_id', $festival->id)
                ->first();

            if (! $booking) {
                continue;
            }

            if ($index < $maxPassengers) {
                $booking->update(['bus_trip_id' => $busTrip->id]);
                $registration->update(['status' => 'on_bus']);
            } else {
                $booking->update(['bus_trip_id' => null]);
                $registration->update(['status' => 'waiting_list']);
            }
        }
    }
}
