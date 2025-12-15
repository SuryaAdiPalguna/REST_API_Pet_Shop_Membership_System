<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get all users successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get('/api/users');
    $response->assertStatus(200);
});
