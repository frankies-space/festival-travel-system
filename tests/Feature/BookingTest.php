<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Festival;
use App\Models\PointsTransaction;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_festivals(): void
    {
        $user = User::factory()->create();
        $festival = Festival::factory()->create(['name' => 'Summer Fest']);

        $response = $this->actingAs($user)->get('/festivals');

        $response->assertOk();
        $response->assertSee('Summer Fest');
    }

    public function test_user_can_book_a_festival_trip(): void
    {
        $user = User::factory()->create(['points_balance' => 0]);
        $festival = Festival::factory()->create([
            'max_capacity' => 50,
            'ticket_price' => 149.00,
        ]);

        $response = $this->actingAs($user)->post("/festivals/{$festival->id}/book");

        $response->assertRedirect('/profile');

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'festival_id' => $festival->id,
            'status' => 'confirmed',
            'price' => 149.00,
        ]);

        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'festival_id' => $festival->id,
            'status' => 'confirmed',
        ]);

        $this->assertSame(config('fts.points_per_booking'), $user->refresh()->points_balance);

        $this->assertDatabaseHas('points_transactions', [
            'user_id' => $user->id,
            'type' => 'earned',
            'amount' => config('fts.points_per_booking'),
        ]);
    }

    public function test_user_cannot_book_the_same_festival_twice(): void
    {
        $user = User::factory()->create();
        $festival = Festival::factory()->create();

        Booking::create([
            'user_id' => $user->id,
            'festival_id' => $festival->id,
            'status' => 'confirmed',
            'price' => 149.00,
            'booked_at' => now(),
        ]);

        $response = $this->actingAs($user)->post("/festivals/{$festival->id}/book");

        $response->assertForbidden();
        $this->assertSame(1, Booking::where('festival_id', $festival->id)->count());
    }

    public function test_user_cannot_book_when_festival_is_full(): void
    {
        $festival = Festival::factory()->create(['max_capacity' => 1]);

        $otherUser = User::factory()->create();
        Booking::create([
            'user_id' => $otherUser->id,
            'festival_id' => $festival->id,
            'status' => 'confirmed',
            'price' => 149.00,
            'booked_at' => now(),
        ]);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post("/festivals/{$festival->id}/book");

        $response->assertForbidden();
        $this->assertDatabaseMissing('bookings', ['user_id' => $user->id]);
    }

    public function test_guest_cannot_access_festivals(): void
    {
        $response = $this->get('/festivals');

        $response->assertRedirect('/login');
    }
}
