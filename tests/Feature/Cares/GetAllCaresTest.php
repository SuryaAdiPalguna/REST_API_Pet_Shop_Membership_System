<?php

use App\Models\Care;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get all cares successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    Care::factory()->create();
    $response = $this->get('/api/cares');
    $response->assertStatus(200);
});
