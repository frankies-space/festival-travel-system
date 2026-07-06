<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Festival;
use App\Models\PointsTransaction;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_shows_points_balance(): void
    {
        $user = User::factory()->create(['points_balance' => 150]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('150');
        $response->assertSee('Puntensaldo');
    }

    public function test_profile_shows_vip_badge_when_threshold_reached(): void
    {
        $user = User::factory()->create(['points_balance' => config('fts.vip_threshold')]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('VIP');
    }

    public function test_phone_number_can_be_updated_on_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '0612345678',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect('/profile');
        $this->assertSame('0612345678', $user->refresh()->phone);
    }

    public function test_profile_shows_travel_history(): void
    {
        $user = User::factory()->create();
        $festival = Festival::factory()->create(['name' => 'Test Festival']);

        Booking::create([
            'user_id' => $user->id,
            'festival_id' => $festival->id,
            'status' => 'confirmed',
            'price' => 149.00,
            'booked_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('Test Festival');
        $response->assertSee('Reisgeschiedenis');
    }

    public function test_profile_shows_empty_travel_history_message(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('Je hebt nog geen reizen geboekt.');
    }
}
