<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //Permisos
        $dashboard = Permission::create([
            'name' => 'dashboard',
            ]);
        $create = Permission::create([
            'name' => 'create',
            ]);
        $update = Permission::create([
            'name' => 'update',
            ]);
        $insert = Permission::create([
            'name' => 'insert',
            ]);
        $delete = Permission::create([
            'name' => 'delete',
            ]);
        $setting = Permission::create([
            'name' => 'setting',
            ]);
        $sequence = Permission::create([
            'name' => 'sequence',
            ]);
        $products = Permission::create([
            'name' => 'products',
            ]);
        $categories = Permission::create([
            'name' => 'categories',
            ]);
        $clients = Permission::create([
            'name' => 'clients',
            ]);
        $inventoy = Permission::create([
            'name' => 'inventory',
            ]);
        $sale = Permission::create([
            'name' => 'sale',
            ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //roles existente
        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'api']);
        $admin = Role::create([
            'name' => 'Admin',
            'guard_name' => 'api']);
        $supervisor = Role::create([
            'name' => 'Supervisor',
            'guard_name' => 'api']);
        $user = Role::create([
            'name' => 'User',
            'guard_name' => 'api']);
        $cashier = Role::create([
            'name' => 'Cashier',
            'guard_name' => 'api']);
        $logistic = Role::create([
            'name' => 'Logistic',
            'guard_name' => 'api']);
        $saler = Role::create([
            'name' => 'Saler',
            'guard_name' => 'api']);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        //Asignar los permisos a diferenes roles

        //Roles de supervisor
        $supervisor->syncPermissions(
            $dashboard,
            $create,
            $update,
            $insert,
            $sequence,
            $products,
            $setting,
            $categories,
            $clients,
            $inventoy,
            $sale);

//        user
        $user->syncPermissions($create,$update);


    }
}
