<?php

use App\Models\Breed;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit breed successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $breed = Breed::factory()->create();
    $response = $this->put("/api/breeds/{$breed->id}", [
        'name' => 'Alaskan Malamute',
    ]);
    $response->assertStatus(200);
});
