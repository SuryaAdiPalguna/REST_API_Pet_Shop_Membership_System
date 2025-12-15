<?php

use App\Models\Adopt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test delete adopt successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $adopt = Adopt::factory()->create();
    $response = $this->delete("/api/adopts/{$adopt->id}");
    $response->assertStatus(200);
});
