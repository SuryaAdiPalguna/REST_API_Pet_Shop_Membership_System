<?php

use App\Models\Puppy;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test delete puppy successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $puppy = Puppy::factory()->create();
    $response = $this->delete("/api/puppies/{$puppy->id}");
    $response->assertStatus(200);
});
