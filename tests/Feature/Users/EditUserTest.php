<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit user successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $response = $this->put("/api/users/{$admin->username}", [
        'name' => 'Admin',
        'phone' => '081234567890',
        'username' => 'superadmin',
        'email' => 'admin@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertStatus(200);
});
