<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BusTrip;
use App\Models\Festival;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusPlanningTest extends TestCase
{
    use RefreshDatabase;

    private function registerUserForFestival(User $user, Festival $festival): void
    {
        Booking::create([
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
    }

    public function test_no_bus_trip_with_34_registrations(): void
    {
        $festival = Festival::factory()->create();

        for ($i = 0; $i < 34; $i++) {
            $this->registerUserForFestival(User::factory()->create(), $festival);
        }

        $this->assertSame(0, BusTrip::where('festival_id', $festival->id)->count());
    }

    public function test_no_bus_trip_with_exactly_35_registrations(): void
    {
        $festival = Festival::factory()->create();

        for ($i = 0; $i < 35; $i++) {
            $this->registerUserForFestival(User::factory()->create(), $festival);
        }

        $this->assertSame(0, BusTrip::where('festival_id', $festival->id)->count());
    }

    public function test_bus_trip_created_with_36_registrations(): void
    {
        $festival = Festival::factory()->create();

        for ($i = 0; $i < 36; $i++) {
            $this->registerUserForFestival(User::factory()->create(), $festival);
        }

        $this->assertSame(1, BusTrip::where('festival_id', $festival->id)->count());
    }

    public function test_vip_gets_priority_when_bus_is_full(): void
    {
        config(['fts.bus_max_passengers' => 1]);

        $festival = Festival::factory()->create();

        $regularUser = User::factory()->create(['points_balance' => 0]);
        $vipUser = User::factory()->create(['points_balance' => 100]);

        for ($i = 0; $i < 35; $i++) {
            $this->registerUserForFestival(User::factory()->create(), $festival);
        }

        $this->registerUserForFestival($regularUser, $festival);
        $this->registerUserForFestival($vipUser, $festival);

        $busTrip = BusTrip::where('festival_id', $festival->id)->first();
        $this->assertNotNull($busTrip);

        $vipBooking = Booking::where('user_id', $vipUser->id)->first();
        $regularBooking = Booking::where('user_id', $regularUser->id)->first();

        $this->assertSame($busTrip->id, $vipBooking->bus_trip_id);
        $this->assertNull($regularBooking->bus_trip_id);

        $this->assertDatabaseHas('registrations', [
            'user_id' => $vipUser->id,
            'status' => 'on_bus',
        ]);
        $this->assertDatabaseHas('registrations', [
            'user_id' => $regularUser->id,
            'status' => 'waiting_list',
        ]);
    }
}
