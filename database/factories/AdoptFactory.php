<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Puppy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adopt>
 */
class AdoptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'puppy_id' => Puppy::factory(),
            'date' => today(),
        ];
    }
}
