<?php

use App\Models\Care;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test delete care successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $care = Care::factory()->create();
    $response = $this->delete("/api/cares/{$care->id}");
    $response->assertStatus(200);
});
