<?php

namespace Tests\Feature;

use App\Models\Festival;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlannerFestivalTest extends TestCase
{
    use RefreshDatabase;

    private function planner(): User
    {
        return User::factory()->create(['role' => 'planner']);
    }

    private function customer(): User
    {
        return User::factory()->create(['role' => 'customer']);
    }

    public function test_planner_can_view_festival_management(): void
    {
        $response = $this->actingAs($this->planner())->get('/planner/festivals');

        $response->assertOk();
    }

    public function test_customer_cannot_access_planner_area(): void
    {
        $response = $this->actingAs($this->customer())->get('/planner/festivals');

        $response->assertForbidden();
    }

    public function test_planner_can_create_festival(): void
    {
        $response = $this->actingAs($this->planner())->post('/planner/festivals', [
            'name' => 'Pinkpop',
            'location' => 'Landgraaf, NL',
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-03',
            'description' => 'Popfestival',
            'max_capacity' => 50,
            'ticket_price' => 179.00,
        ]);

        $response->assertRedirect('/planner/festivals');
        $this->assertDatabaseHas('festivals', ['name' => 'Pinkpop']);
    }

    public function test_planner_can_update_festival(): void
    {
        $festival = Festival::factory()->create(['name' => 'Oud Festival']);
        $planner = $this->planner();

        $response = $this->actingAs($planner)->put("/planner/festivals/{$festival->id}", [
            'name' => 'Nieuw Festival',
            'location' => $festival->location,
            'start_date' => $festival->start_date->format('Y-m-d'),
            'end_date' => $festival->end_date->format('Y-m-d'),
            'max_capacity' => $festival->max_capacity,
            'ticket_price' => $festival->ticket_price,
        ]);

        $response->assertRedirect('/planner/festivals');
        $this->assertDatabaseHas('festivals', ['name' => 'Nieuw Festival']);
    }

    public function test_planner_can_delete_festival(): void
    {
        $festival = Festival::factory()->create();
        $planner = $this->planner();

        $response = $this->actingAs($planner)->delete("/planner/festivals/{$festival->id}");

        $response->assertRedirect('/planner/festivals');
        $this->assertDatabaseMissing('festivals', ['id' => $festival->id]);
    }
}
