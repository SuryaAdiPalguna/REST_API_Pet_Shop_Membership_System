<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Care;
use App\Models\Puppy;
use App\Models\PuppyCare;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PuppySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Puppy::factory(10)->create([
            'breed_id' => Breed::inRandomOrder()->first()->id,
        ])->each(function ($puppy) {
            PuppyCare::factory()->create([
                'puppy_id' => $puppy->id,
                'care_id' => Care::inRandomOrder()->first()->id,
            ]);
        });
    }
}
