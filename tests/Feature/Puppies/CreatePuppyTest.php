<?php

use App\Models\Breed;
use App\Models\Care;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test create puppy successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $breed = Breed::factory()->create();
    $care = Care::factory()->create();
    $response = $this->post('/api/puppies', [
        'breed_id' => $breed->id,
        'name' => 'Puppy',
        'puppy_cares' => [
            [
                'care_id' => $care->id,
                'period' => 30,
            ],
        ],
    ]);
    $response->assertStatus(201);
});
