<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Care>
 */
class CareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucwords(fake()->sentence(fake()->numberBetween(2, 4))),
            'description' => fake()->sentence(fake()->numberBetween(4, 8)),
        ];
    }
}
