<?php

namespace Tests\Feature;

use App\Models\Festival;
use App\Models\PointsTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointsRedemptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_redeem_points_for_discount(): void
    {
        $user = User::factory()->create(['points_balance' => 100, 'available_discount' => 0]);

        $response = $this->actingAs($user)->post('/points/redeem-discount', [
            'points' => 100,
        ]);

        $response->assertRedirect('/profile');
        $user->refresh();

        $this->assertSame(0, $user->points_balance);
        $this->assertEquals(10.00, (float) $user->available_discount);

        $this->assertDatabaseHas('points_transactions', [
            'user_id' => $user->id,
            'type' => 'redeemed_discount',
            'amount' => -100,
        ]);
    }

    public function test_user_cannot_redeem_more_points_than_balance(): void
    {
        $user = User::factory()->create(['points_balance' => 30]);

        $response = $this->actingAs($user)->post('/points/redeem-discount', [
            'points' => 50,
        ]);

        $response->assertSessionHasErrors('points');
        $this->assertSame(30, $user->refresh()->points_balance);
    }

    public function test_user_cannot_redeem_below_minimum_points(): void
    {
        $user = User::factory()->create(['points_balance' => 100]);

        $response = $this->actingAs($user)->post('/points/redeem-discount', [
            'points' => 30,
        ]);

        $response->assertSessionHasErrors('points');
    }

    public function test_user_can_redeem_points_for_vip_access(): void
    {
        $user = User::factory()->create([
            'points_balance' => 90,
            'vip_until' => null,
        ]);

        $response = $this->actingAs($user)->post('/points/redeem-vip');

        $response->assertRedirect('/profile');
        $user->refresh();

        $this->assertSame(10, $user->points_balance);
        $this->assertTrue($user->isVip());
        $this->assertNotNull($user->vip_until);

        $this->assertDatabaseHas('points_transactions', [
            'user_id' => $user->id,
            'type' => 'redeemed_vip',
            'amount' => -80,
        ]);
    }

    public function test_user_cannot_redeem_vip_when_already_vip_via_balance(): void
    {
        $user = User::factory()->create([
            'points_balance' => 150,
            'vip_until' => null,
        ]);

        $response = $this->actingAs($user)->post('/points/redeem-vip');

        $response->assertSessionHasErrors('vip');
    }

    public function test_discount_is_applied_on_booking(): void
    {
        $user = User::factory()->create([
            'points_balance' => 0,
            'available_discount' => 20.00,
        ]);
        $festival = Festival::factory()->create(['ticket_price' => 149.00]);

        $response = $this->actingAs($user)->post("/festivals/{$festival->id}/book");

        $response->assertRedirect('/profile');

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'price' => 129.00,
        ]);

        $this->assertEquals(0, (float) $user->refresh()->available_discount);
    }

    public function test_discount_cannot_exceed_ticket_price(): void
    {
        $user = User::factory()->create([
            'points_balance' => 0,
            'available_discount' => 200.00,
        ]);
        $festival = Festival::factory()->create(['ticket_price' => 149.00]);

        $this->actingAs($user)->post("/festivals/{$festival->id}/book");

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'price' => 0,
        ]);

        $this->assertEquals(51.00, (float) $user->refresh()->available_discount);
    }
}
