<?php

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit member successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $member = Member::factory()->create();
    $response = $this->put("/api/members/{$member->id}", [
        'name' => 'Member',
        'phone' => '081234567890',
        'email' => 'member@gmail.com',
        'address' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
    ]);
    $response->assertStatus(200);
});
