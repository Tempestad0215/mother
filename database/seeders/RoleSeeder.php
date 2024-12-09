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
            'guard_name' => 'api']);
        $create = Permission::create([
            'name' => 'create',
            'guard_name' => 'api']);
        $update = Permission::create([
            'name' => 'update',
            'guard_name' => 'api']);
        $insert = Permission::create([
            'name' => 'insert',
            'guard_name' => 'api']);
        $delete = Permission::create([
            'name' => 'delete',
            'guard_name' => 'api']);
        $setting = Permission::create([
            'name' => 'setting',
            'guard_name' => 'api']);
        $sequence = Permission::create([
            'name' => 'sequence',
            'guard_name' => 'api']);
        $products = Permission::create([
            'name' => 'products',
            'guard_name' => 'api']);
        $categories = Permission::create([
            'name' => 'categories',
            'guard_name' => 'api']);
        $clients = Permission::create([
            'name' => 'clients',
            'guard_name' => 'api']);
        $inventoy = Permission::create([
            'name' => 'inventory',
            'guard_name' => 'api']);
        $sale = Permission::create([
            'name' => 'sale',
            'guard_name' => 'api']);

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
