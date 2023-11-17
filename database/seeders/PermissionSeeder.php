<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $roleSuperAdmin = Role::create(['name' => 'Super Admin']);
        $roleAdmin = Role::create(['name' => 'Admin']);


        $roleInventoryView = Role::create(['name' => 'Inventory']);
        $roleInventoryCycle = Role::create(['name' => 'Inventory Cycle']);
        $roleInventoryEdit = Role::create(['name' => 'Inventory Manage']);

        $permissionInventoryNegativeQuantities = Permission::create(['name' => 'view negative quantities']);
        $permissionInventoryQUAView = Permission::create(['name' => 'view qua']);
        $permissionInventoryQUAEdit = Permission::create(['name' => 'update qua']);
        $permissionInventoryCycleView = Permission::create(['name' => 'view cycle count']);
        $permissionInventoryCycleCreate = Permission::create(['name' => 'create cycle count']);
        $permissionInventoryCycleUpdate = Permission::create(['name' => 'update cycle count']);
        $permissionInventoryCycleDelete = Permission::create(['name' => 'delete cycle count']);
        $permissionInventoryView = Permission::create(['name' => 'view inventory']);
        $permissionInventoryEdit = Permission::create(['name' => 'update inventory']);
        $permissionViewDollars = Permission::create(['name' => 'view dollars']);

        $permissionsInventory = [$permissionInventoryView, $permissionInventoryQUAView, $permissionInventoryNegativeQuantities];
        $permissionsInventoryCycle = [$permissionInventoryView, $permissionInventoryQUAView, $permissionInventoryNegativeQuantities, $permissionInventoryCycleView, $permissionInventoryCycleCreate, $permissionInventoryCycleUpdate];
        $permissionsInventoryManage = [$permissionInventoryView, $permissionInventoryQUAView, $permissionInventoryNegativeQuantities, $permissionInventoryCycleView, $permissionInventoryCycleUpdate, $permissionInventoryQUAEdit, $permissionInventoryEdit];

        $roleInventoryView->syncPermissions($permissionsInventory);
        $roleInventoryCycle->syncPermissions($permissionsInventoryCycle);
        $roleInventoryEdit->syncPermissions($permissionsInventoryManage);

        $roleAdmin->syncPermissions([$permissionViewDollars, $permissionInventoryCycleDelete]);



    }
}
