<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['module_id' =>1,  'name'=>'Dashboard Access', 'slug' => 'dashboard-access', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],

            ['module_id' => 4, 'name'=> 'User Access', 'slug' => 'user-access', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=>NULL],
            ['module_id' => 4, 'name'=> 'User Add', 'slug' => 'user-add', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 4, 'name'=> 'User Edit', 'slug' => 'user-edit', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 4, 'name'=> 'User View', 'slug' => 'user-view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 4, 'name'=> 'User Delete', 'slug' => 'user-delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 4, 'name'=> 'User Bulk Delete', 'slug' => 'user-bulk-delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 4, 'name'=> 'User Report', 'slug' => 'user-report', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],

            ['module_id' => 5, 'name'=> 'Role Access', 'slug' => 'role-access', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 5, 'name'=> 'Role Add', 'slug' => 'role-add', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 5, 'name'=> 'Role Edit', 'slug' => 'role-edit', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 5, 'name'=> 'Role View', 'slug' => 'role-view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 5, 'name'=> 'Role Delete', 'slug' => 'role-delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 5, 'name'=> 'Role Bulk Delete', 'slug' => 'role-bulk-delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 5, 'name'=> 'Role Report', 'slug' => 'role-report', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],

            ['module_id' => 7, 'name'=> 'Setting Access', 'slug' => 'setting-access', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],

            ['module_id' => 8, 'name'=> 'Menu Access', 'slug' => 'menu-access','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 8, 'name'=> 'Menu Add', 'slug' => 'menu-add','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 8, 'name'=> 'Menu Edit', 'slug' => 'menu-edit','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 8, 'name'=> 'Menu Delete', 'slug' => 'menu-delete','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 8, 'name'=> 'Menu Bulk Delete', 'slug' => 'menu-bulk-delete','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 8, 'name'=> 'Menu Report', 'slug' => 'menu-report','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 8, 'name'=> 'Menu Builder Access','slug' =>  'menu-builder-access','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 8, 'name'=> 'Menu Module Add', 'slug' => 'menu-module-add','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 8, 'name'=> 'Menu Module Edit', 'slug' => 'menu-module-edit','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 8, 'name'=> 'Menu Module Delete', 'slug' => 'menu-module-delete','created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],

            ['module_id' => 9, 'name'=> 'Permission Access', 'slug' => 'permission-access', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 9, 'name'=> 'Permission Add', 'slug' => 'permission-add', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 9, 'name'=> 'Permission Edit', 'slug' => 'permission-edit', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 9, 'name'=> 'Permission Delete', 'slug' => 'permission-delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 9, 'name'=> 'Permission Bulk Delete', 'slug' => 'permission-bulk-delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
            ['module_id' => 9, 'name'=> 'Permission Report', 'slug' => 'permission-report', 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=> NULL],
        ];

        Permission::insert($permissions);
    }
}
