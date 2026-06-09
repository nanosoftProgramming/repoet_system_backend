<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run()
    // {
    //     $admin = $this->adminCreation();
    //     // $this->permissionCreation();
    //     $role = $this->roleCreation();
    //     $role2 = $this->role2Creation();
    //     $admin->assignRole($role);
    //     $this->role3Creation();
    // }

    public function run()
{
    // 1. إنشاء الأدوار فقط إذا لم تكن موجودة
    $role1 = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'user']);
    $role2 = Role::firstOrCreate(['name' => 'Trainer', 'guard_name' => 'user']);
    $role3 = Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'user']);

    // 2. إنشاء الـ admin فقط إذا لم يكن موجوداً
    $admin = User::where('email', 'admin@admin.com')->first();
    if (!$admin) {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '0123456789',
            'identity_number' => '1234567890',
            'role' => 'Super Admin',
            'password' => Hash::make('123123'),
            'is_active' => 1,
        ]);
        $admin->assignRole($role1);
    }
}

    function adminCreation()
    {
        return $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '0123456789',
            'identity_number' => '1234567890',
            'role' => 'Super Admin',
            'password' => Hash::make('123123'),
            'is_active' => 1,
        ]);
    }

    function permissionCreation()
    {
        // $permissions = [
        //     ['Index-user', 'User', 'Index'],
        //     ['Create-user', 'User', 'Create'],
        //     ['Edit-user', 'User', 'Edit'],
        //     ['Delete-user', 'User', 'Delete'],

        //     ['Index-role', 'Roles', 'Index'],
        //     ['Create-role', 'Roles', 'Create'],
        //     ['Edit-role', 'Roles', 'Edit'],
        //     ['Delete-role', 'Roles', 'Delete'],
        // ];

        // foreach ($permissions as $permission) {
        //     Permission::create(['name' => $permission[0], 'category' => $permission[1], 'guard_name' => 'user', 'display' => $permission[2]]);
        // }
    }

    function roleCreation()
    {
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'user']);
        // $permissions = Permission::all();
        // $role->syncPermissions($permissions);
        return $role;
    }

    function role2Creation()
    {
        $role = Role::create(['name' => 'Trainer', 'guard_name' => 'user']);
        // $permissions = Permission::whereIn('category',)->get();
        // $role->syncPermissions($permissions);
        return $role;
    }
    function role3Creation()
    {
        $role = Role::create(['name' => 'Student', 'guard_name' => 'user']);
        // $permissions = Permission::whereIn('category',)->get();
        // $role->syncPermissions($permissions);
        return $role;
    }
}
