<?php

use App\Models\Breed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get all breeds successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    Breed::factory()->create();
    $response = $this->get('/api/breeds');
    $response->assertStatus(200);
});
