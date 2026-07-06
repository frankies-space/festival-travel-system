<?php

namespace Database\Factories;

use App\Models\Festival;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Festival>
 */
class FestivalFactory extends Factory
{
    protected $model = Festival::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 month', '+6 months');

        return [
            'name' => fake()->words(2, true).' Festival',
            'location' => fake()->city().', '.fake()->country(),
            'start_date' => $startDate,
            'end_date' => (clone $startDate)->modify('+'.fake()->numberBetween(2, 4).' days'),
            'description' => fake()->paragraph(),
            'max_capacity' => fake()->numberBetween(40, 100),
            'ticket_price' => fake()->randomElement([129.00, 149.00, 169.00, 199.00]),
        ];
    }
}
