<?php

use App\Models\Adopt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get all adopts successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    Adopt::factory()->create();
    $response = $this->get('/api/adopts');
    $response->assertStatus(200);
});
