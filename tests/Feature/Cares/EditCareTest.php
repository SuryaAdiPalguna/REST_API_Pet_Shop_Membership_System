<?php

use App\Models\Care;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit care successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $care = Care::factory()->create();
    $response = $this->put("/api/cares/{$care->id}", [
        'name' => 'Vaccination',
        'description' => 'Vaccination to prevent disease in pets.',
    ]);
    $response->assertStatus(200);
});
