<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit user successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $response = $this->put("/api/users/{$user->username}", [
        'name' => 'Admin',
        'phone' => '081234567890',
        'username' => 'superadmin',
        'email' => 'admin@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertStatus(200);
});
