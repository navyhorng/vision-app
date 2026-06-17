<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * php artisan db:seed --class=PermissionSeeder
     */
    public function run(): void
    {
        $resources = ['user', 'role', 'permission', 'product', 'ocr-result'];
        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissionName = "{$action} {$resource}";
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }
        }

        $admin = Role::findByName('admin', 'web');
        $user = Role::findByName('user', 'web');


        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo(['name', 'like', 'view %'])->get();
    }
}
