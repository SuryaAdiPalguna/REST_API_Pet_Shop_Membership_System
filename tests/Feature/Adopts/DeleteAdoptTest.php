<?php

use App\Models\Adopt;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test delete adopt successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $adopt = Adopt::factory()->create();
    $response = $this->delete("/api/adopts/{$adopt->id}");
    $response->assertStatus(200);
});
