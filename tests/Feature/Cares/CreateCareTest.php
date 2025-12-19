<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test create care successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $response = $this->post('/api/cares', [
        'name' => 'Vaccination',
        'description' => 'Vaccination to prevent disease in pets.',
    ]);
    $response->assertStatus(201);
});
