<?php

use App\Models\Puppy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get all puppies successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    Puppy::factory()->create();
    $response = $this->get('/api/puppies');
    $response->assertStatus(200);
});
