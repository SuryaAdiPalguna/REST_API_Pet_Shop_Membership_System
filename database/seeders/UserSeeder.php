<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'phone' => '081234567890',
            'username' => 'superadmin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password')
        ]);
        $user->assignRole('admin');
    }
}
