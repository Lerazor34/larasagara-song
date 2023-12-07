<?php

namespace Database\Seeders;

use App\Models\Priveleges;
use App\Models\RolePriveleges;
use App\Models\Roles;
use App\Models\RoleUsers;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserManagement extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = [
            'name' => 'super admin'
        ];

        Roles::create($role);

        $privilege = [
            'module' => 'All Access',
            'sub_module' => 'All Access',
            'module_name' => 'All Access',
            'namespace' => '*',
            'ordering' => 1
        ];

        Priveleges::create($privilege);

        $rolePrivilege = [
            'role' => '1',
            'namespace' => '*'
        ];

        RolePriveleges::create($rolePrivilege);

        $users = [
            'photo' => null,
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'super_admin',
            'email' => 'superadmin@gmail.com',
            'gender' => 'Male',
            'address' => 'Jakarta',
            'phone_number' => '0000',
            'password' => bcrypt('password')
        ];

        User::create($users);

        $roleUser = [
            'users_id' => 1,
            'roles_id' => 1
        ];

        RoleUsers::create($roleUser);
    }
}
