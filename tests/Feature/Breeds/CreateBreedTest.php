<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test create breed successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->post('/api/breeds', [
        'name' => 'Alaskan Malamute',
    ]);
    $response->assertStatus(201);
});
