<?php

namespace Database\Seeders;

use App\Models\Breed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $breeds = [
            'Alaskan Malamute',
            'Akita',
            'Beagle',
            'Belgian Malinois',
            'Boxer',
            'Cihuahua',
            'Dachshund',
            'Doberman Pinscher',
            'English Bulldog',
            'German Sheperd',
            'Golden Retriever',
            'Labrador Retriever',
            'Korean Jindo',
            'Maltese',
            'Mini Pinscher',
            'Rottweiler',
            'Pekingese',
            'Pomeranian',
            'Shis Tzu',
            'Shiba Inu',
        ];
        foreach ($breeds as $breed) {
            Breed::factory()->create([
                'name' => $breed,
            ]);
        }
    }
}
