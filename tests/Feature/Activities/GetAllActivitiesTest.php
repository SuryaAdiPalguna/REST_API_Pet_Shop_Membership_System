<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get all activities successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $superadmin = User::factory()->create();
    $superadmin->assignRole('superadmin');
    Sanctum::actingAs($superadmin);
    $this->post('/api/members', [
        'name' => 'Member',
        'phone' => '081234567890',
        'email' => 'member@gmail.com',
        'address' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
    ]);
    $response = $this->get('/api/activities');
    $response->assertStatus(200);
});
