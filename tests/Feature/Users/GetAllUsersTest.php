<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get all users successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $superadmin = User::factory()->create();
    $superadmin->assignRole('superadmin');
    Sanctum::actingAs($superadmin);
    $response = $this->get('/api/users');
    $response->assertStatus(200);
});
