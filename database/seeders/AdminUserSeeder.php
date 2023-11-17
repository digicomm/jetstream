<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
/*
        $superAdminRole = Role::create(['name' => 'Super Administrator']);

        $adminRole = Role::create(['name' => 'Administrator']);
        $permission = Permission::create(['name' => 'view dollars']);
        $permission->assignRole($adminRole);
        $permission->assignRole($superAdminRole);
*/

        $adminUser = User::find('9a0fc0e5-c8c8-4835-9817-40139b0f05f2');
        $adminUser->assignRole('Super Admin');
        $adminUser->assignRole('Admin');
        $adminUser->assignRole('Inventory');
        $adminUser->assignRole('Inventory Cycle');
        $adminUser->assignRole('Inventory Manage');

    }
}