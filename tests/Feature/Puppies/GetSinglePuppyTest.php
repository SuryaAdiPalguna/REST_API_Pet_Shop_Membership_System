<?php

use App\Models\Puppy;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test get single puppy successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $puppy = Puppy::factory()->create();
    $response = $this->get("/api/puppies/{$puppy->id}");
    $response->assertStatus(200);
});
