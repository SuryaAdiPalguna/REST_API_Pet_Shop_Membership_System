<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
        $this->call([UserSeeder::class, MemberSeeder::class, BreedSeeder::class, CareSeeder::class]);
        $this->call(PuppySeeder::class);
        $this->call(AdoptSeeder::class);
    }
}
