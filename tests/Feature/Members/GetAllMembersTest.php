<?php

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get all members successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    Member::factory()->create();
    $response = $this->get('/api/members');
    $response->assertStatus(200);
});
