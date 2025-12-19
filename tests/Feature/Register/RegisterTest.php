<?php

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('test register successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $response = $this->post('/api/register', [
        'name' => 'Admin Puppy',
        'phone' => '089876543210',
        'username' => 'admin_puppy',
        'email' => 'admin_puppy@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertStatus(201);
});
