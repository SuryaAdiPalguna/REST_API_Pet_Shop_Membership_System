<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test delete user successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->delete("/api/users/{$user->username}");
    $response->assertStatus(200);
});
