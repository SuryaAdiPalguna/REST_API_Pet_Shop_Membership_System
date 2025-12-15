<?php

use App\Models\Member;
use App\Models\Puppy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test create adopt successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $member = Member::factory()->create();
    $puppy = Puppy::factory()->create();
    $response = $this->post('/api/adopts', [
        'member_id' => $member->id,
        'puppy_id' => $puppy->id,
        'date' => today()->format('Y-m-d'),
        'note' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
    ]);
    $response->assertStatus(201);
});
