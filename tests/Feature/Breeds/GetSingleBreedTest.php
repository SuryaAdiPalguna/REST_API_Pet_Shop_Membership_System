<?php

use App\Models\Breed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get single breed successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $breed = Breed::factory()->create();
    $response = $this->get("/api/breeds/{$breed->id}");
    $response->assertStatus(200);
});
