<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('test login successfully', function () {
    User::factory()->create([
        'email' => 'admin@gmail.com',
        'password' => Hash::make('password'),
    ]);
    $response = $this->post("/api/login", [
        'email' => 'admin@gmail.com',
        'password' => 'password',
    ]);
    $response->assertStatus(200);
});
