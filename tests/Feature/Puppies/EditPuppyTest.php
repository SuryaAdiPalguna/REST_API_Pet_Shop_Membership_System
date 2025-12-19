<?php

use App\Models\Breed;
use App\Models\Care;
use App\Models\Puppy;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('test edit puppy successfully', function () {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin);
    $breed = Breed::factory()->create();
    $care = Care::factory()->create();
    $puppy = Puppy::factory()->create();
    $response = $this->put("/api/puppies/{$puppy->id}", [
        'breed_id' => $breed->id,
        'name' => 'Puppy',
        'puppy_cares' => [
            [
                'care_id' => $care->id,
                'period' => 30,
            ],
        ],
    ]);
    $response->assertStatus(200);
});
