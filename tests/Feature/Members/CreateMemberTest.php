<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test create member successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $response = $this->post('/api/members', [
        'name' => 'Member',
        'phone' => '081234567890',
        'email' => 'member@gmail.com',
        'address' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
    ]);
    $response->assertStatus(201);
});
