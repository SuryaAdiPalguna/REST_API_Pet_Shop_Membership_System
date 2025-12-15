<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test create member successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->post('/api/members', [
        'name' => 'Member',
        'phone' => '081234567890',
        'email' => 'member@gmail.com',
        'address' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
    ]);
    $response->assertStatus(201);
});
