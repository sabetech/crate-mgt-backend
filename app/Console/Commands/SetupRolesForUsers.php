<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupRolesForUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-roles-for-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command create roles for users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Creating roles...');
        //check if roles and permissions exist
        $roles = [
            'admin',
            'empties_manager',
            'operations_manager',
            'auditor',
            'sales_manager',
            'sales_admin',
            'cashier',
            'warehouse_manager',
        ];

        $permissions = [
            0 => 'view_dashboard',
            1 => 'create_customer',
            2 => 'view_customer',
            3 => 'list_customers',
            4 => 'return_empties',
            5 => 'record_vse_sale',
            6 => 'empties_sales_in',
            7 => 'empties_returned',
            8 => 'empties_on_ground',
            9 => 'inventory',
            10 => 'initial_sale',
            11 => 'list_sales',
            12 => 'modify_prices',
            13 => 'approve_sale',
            14 => 'approve',
            15 => 'user_management',
            16 => 'return_sales',
            17 => 'record_sales',
            18 => 'reports',
        ];

        $permissionsForEmptiesManager = [
            $permissions[0], 
            $permissions[2],
            $permissions[3],
            $permissions[4],
            $permissions[5],
            $permissions[6],
            $permissions[7],
            $permissions[8],
        ];

        $permissionsForSalesManager = [
            $permissions[10],
            $permissions[11],
        ];

        $permissionsForSalesAdmin = [
            $permissions[10],
            $permissions[11],
            $permissions[16],
            $permissions[17],
        ];

        $permissionsForCashier = [
            $permissions[11],
            $permissions[13],
        ];

        $permissionsForWarehouseManager = [
            $permissions[9],
        ];

        $permissionsForAuditor = [
            $permissions[18],
        ];

        $permissionsForOperationsManager = [
            $permissions[0],
            $permissions[1],
            $permissions[4],
            $permissions[5],
            $permissions[6],
            $permissions[7],
            $permissions[8],
            $permissions[9],
            $permissions[12],
            $permissions[13],
            $permissions[14],
            $permissions[15],
            $permissions[16],
        ];

        foreach ($roles as $role) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
            $this->info("Role {$role->name} created");
        }

        foreach ($permissions as $permission) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
            $this->info("Permission {$permission->name} created");
        }

        $this->info('Assigning permissions to roles...');
    
        $role = \Spatie\Permission\Models\Role::findByName('admin');
        foreach($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        $role = \Spatie\Permission\Models\Role::findByName('empties_manager');
        foreach($permissionsForEmptiesManager as $permission) {
            $role->givePermissionTo($permission);
        }

        $role = \Spatie\Permission\Models\Role::findByName('operations_manager');
        foreach($permissionsForEmptiesManager as $permission) {
            $role->givePermissionTo($permission);
        }

        $role = \Spatie\Permission\Models\Role::findByName('sales_manager');
        foreach($permissionsForSalesManager as $permission) {
            $role->givePermissionTo($permission);
        }

        $role = \Spatie\Permission\Models\Role::findByName('cashier');
        foreach($permissionsForCashier as $permission) {
            $role->givePermissionTo($permission);
        }

        $role = \Spatie\Permission\Models\Role::findByName('warehouse_manager');
        $role->givePermissionTo('inventory');


        $this->info('Permissions assigned to admin role');
        
    }
}
