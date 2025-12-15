<?php

use App\Models\Breed;
use App\Models\Care;
use App\Models\Puppy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit puppy successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $breed = Breed::factory()->create();
    $care = Care::factory()->create();
    $puppy = Puppy::factory()->create();
    $response = $this->put("/api/puppies/{$puppy->id}", [
        'breed_id' => $breed->id,
        'name' => 'Puppy',
        'puppy_cares' => [
            [
                'care_id' => $care->id,
                'period' => 30,
            ],
        ],
    ]);
    $response->assertStatus(200);
});
