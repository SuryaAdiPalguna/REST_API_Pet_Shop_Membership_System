<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('test register successfully', function () {
    $response = $this->post('/api/register', [
        'name' => 'Admin',
        'phone' => '081234567890',
        'username' => 'superadmin',
        'email' => 'admin@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertStatus(201);
});
