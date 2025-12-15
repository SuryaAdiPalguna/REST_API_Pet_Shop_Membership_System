<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test create care successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->post('/api/cares', [
        'name' => 'Vaccination',
        'description' => 'Vaccination to prevent disease in pets.',
    ]);
    $response->assertStatus(201);
});
