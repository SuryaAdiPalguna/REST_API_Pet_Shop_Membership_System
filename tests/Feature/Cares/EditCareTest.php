<?php

use App\Models\Care;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit care successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $care = Care::factory()->create();
    $response = $this->put("/api/cares/{$care->id}", [
        'name' => 'Vaccination',
        'description' => 'Vaccination to prevent disease in pets.',
    ]);
    $response->assertStatus(200);
});
