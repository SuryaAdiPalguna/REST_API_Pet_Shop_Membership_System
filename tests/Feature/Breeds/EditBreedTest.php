<?php

use App\Models\Breed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit breed successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $breed = Breed::factory()->create();
    $response = $this->put("/api/breeds/{$breed->id}", [
        'name' => 'Alaskan Malamute',
    ]);
    $response->assertStatus(200);
});
