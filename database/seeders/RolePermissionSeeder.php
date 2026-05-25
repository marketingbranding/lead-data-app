<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $resources = [
            'banks', 'kavlings', 'sales', 'promos', 'konsumens',
            'bi-checkings', 'psjbs', 'pemberkasans', 'proses-banks', 'ppjb-devs',
            'akads', 'basts', 'expenses', 'pipeline-logs', 'lead-times',
            'roles', 'permissions', 'users', 'cabangs',
        ];

        $actions = ['view-any', 'view', 'create', 'update', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::findOrCreate("{$resource}.{$action}", 'web');
            }
        }

        $superAdmin = Role::findOrCreate('super-admin', 'web');
        $admin = Role::findOrCreate('admin', 'web');
        $adminCabang = Role::findOrCreate('admin-cabang', 'web');

        $admin->syncPermissions(Permission::all());
        $adminCabang->syncPermissions(Permission::all());

        $user = \App\Models\User::where('email', 'admin@leaddata.com')->first();
        if ($user) {
            $user->assignRole('super-admin');
        }
    }
}
