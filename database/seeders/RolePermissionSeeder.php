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
        $features = ['users', 'members', 'breeds', 'cares', 'puppies', 'adopts', 'activities'];
        foreach ($features as $feature) {
            Permission::create(['name' => "{$feature}.store"]) && ($feature !== 'users' || $feature !== 'activities');
            Permission::create(['name' => "{$feature}.update"]) && ($feature !== 'adopts' || $feature !== 'activities');
            Permission::create(['name' => "{$feature}.index"]);
            Permission::create(['name' => "{$feature}.show"]) && $feature !== 'activities';
            Permission::create(['name' => "{$feature}.destroy"]) && $feature !== 'activities';
        }
        $superadmin = Role::create(['name' => 'superadmin']);
        $superadmin->syncPermissions(Permission::all());
        $admin = Role::create(['name' => 'admin']);
        $admin->syncPermissions(Permission::whereNotIn('name', ['users.index', 'users.destroy', 'activities.index'])->get());
    }
}
