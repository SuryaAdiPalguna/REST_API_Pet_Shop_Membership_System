<?php

namespace Database\Seeders;

use App\Models\Care;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cares = [
            [
                'name' => 'Vaccination',
                'description' => 'Vaccination to prevent disease in pets.',
            ],
            [
                'name' => 'Deworming',
                'description' => 'Medication to treat worm infections in pets.',
            ],
            [
                'name' => 'Flea Treatment',
                'description' => 'Treatment to remove fleas and prevent infection.',
            ],
            [
                'name' => 'Sterilization',
                'description' => 'Medical procedures to prevent reproduction in pets.',
            ],
            [
                'name' => 'Health Checks',
                'description' => 'Routine health checks for pets.',
            ],
            [
                'name' => 'Dental Care',
                'description' => 'Cleaning and maintaining pets teeth.',
            ],
            [
                'name' => 'Fungal Treatment',
                'description' => 'Treatment to treat fungal skin infections.',
            ],
            [
                'name' => 'Vitamins and Supplements',
                'description' => 'Vitamins and supplements for animal health.',
            ],
        ];
        foreach ($cares as $care) {
            Care::factory()->create([
                'name' => $care['name'],
                'description' => $care['description'],
            ]);
        }
    }
}
