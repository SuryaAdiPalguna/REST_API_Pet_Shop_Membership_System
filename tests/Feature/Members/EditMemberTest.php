<?php

use App\Models\Member;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit member successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $member = Member::factory()->create();
    $response = $this->put("/api/members/{$member->id}", [
        'name' => 'Member',
        'phone' => '081234567890',
        'email' => 'member@gmail.com',
        'address' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'is_active' => false,
    ]);
    $response->assertStatus(200);
});
