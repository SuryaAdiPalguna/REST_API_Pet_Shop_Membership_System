<?php

namespace Database\Seeders;

use App\Models\Adopt;
use App\Models\Member;
use App\Models\Puppy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdoptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Adopt::factory()->create([
            'member_id' => Member::inRandomOrder()->first()->id,
            'puppy_id' => Puppy::inRandomOrder()->first()->id,
        ]);
    }
}
