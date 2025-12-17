<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = ['users', 'members', 'breeds', 'cares', 'puppies', 'adopts'];
        foreach ($features as $feature) {
            Permission::create([
                'name' => "{$feature}.store"
            ]) && $feature !== 'users';
            Permission::create([
                'name' => "{$feature}.update"
            ]) && $feature !== 'adopts';
            Permission::create([
                'name' => "{$feature}.index"
            ]);
            Permission::create([
                'name' => "{$feature}.show"
            ]);
            Permission::create([
                'name' => "{$feature}.destroy"
            ]);
        }
        $admin = Role::create([
            'name' => 'admin'
        ]);
        $admin->syncPermissions(Permission::all());
    }
}
