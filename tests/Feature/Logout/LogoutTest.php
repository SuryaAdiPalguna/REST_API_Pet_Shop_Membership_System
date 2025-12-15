<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test logout successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->post("/api/logout", []);
    $response->assertStatus(200);
});
