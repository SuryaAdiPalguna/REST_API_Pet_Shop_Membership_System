<?php

use App\Models\Puppy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get single puppy successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $puppy = Puppy::factory()->create();
    $response = $this->get("/api/puppies/{$puppy->id}");
    $response->assertStatus(200);
});
