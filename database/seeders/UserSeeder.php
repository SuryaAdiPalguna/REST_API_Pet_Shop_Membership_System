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
        $superadmin = User::factory()->create([
            'name' => 'Superadmin Puppy',
            'phone' => '081234567890',
            'username' => 'superadmin_puppy',
            'email' => 'superadmin_puppy@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $admin = User::factory()->create([
            'name' => 'Admin Puppy',
            'phone' => '089876543210',
            'username' => 'admin_puppy',
            'email' => 'admin_puppy@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $superadmin->assignRole('superadmin');
        $admin->assignRole('admin');
    }
}
