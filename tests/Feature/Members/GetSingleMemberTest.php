<?php

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get single member successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $member = Member::factory()->create();
    $response = $this->get("/api/members/{$member->id}");
    $response->assertStatus(200);
});
