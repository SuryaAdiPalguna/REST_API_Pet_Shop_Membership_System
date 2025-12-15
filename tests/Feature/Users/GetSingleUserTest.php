<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get single user successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->get("/api/users/{$user->username}");
    $response->assertStatus(200);
});
