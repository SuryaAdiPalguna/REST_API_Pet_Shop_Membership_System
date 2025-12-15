<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = ['user', 'member', 'breed', 'care', 'puppy', 'adopt'];
        foreach ($features as $feature) {
            if ($feature !== 'user')
                Permission::create([
                    'name' => "create_{$feature}",
                ]);
            if ($feature !== 'adopt')
                Permission::create([
                    'name' => "edit_{$feature}",
                ]);
            Permission::create([
                'name' => 'get_all_' . Str::plural($feature),
            ]);
            Permission::create([
                'name' => "get_single_{$feature}",
            ]);
            Permission::create([
                'name' => "delete_{$feature}",
            ]);
        }
        $admin = Role::create([
            'name' => 'admin'
        ]);
        $admin->givePermissionTo(Permission::all());
    }
}
