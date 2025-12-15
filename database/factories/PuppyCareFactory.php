<?php

namespace Database\Factories;

use App\Models\Care;
use App\Models\Puppy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PuppyCare>
 */
class PuppyCareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $periods = [30, 45, 60, 90, 180, 365];
        return [
            'puppy_id' => Puppy::factory(),
            'care_id' => Care::factory(),
            'period' => $periods[fake()->numberBetween(0, count($periods) - 1)],
        ];
    }
}
