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
            'clerk',
        ];

        $permissions = [
            'view',
            'edit',
            'create',
            'delete',
            'approve',
            'user_management',
        ]; //let do this for now ... I can research more on this later

        foreach ($roles as $role) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
            $this->info("Role {$role->name} created");
        }

        foreach ($permissions as $permission) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
            $this->info("Permission {$permission->name} created");
        }

        $this->info('Assigning permissions to roles...');
        //assign permissions to roles
        $role = \Spatie\Permission\Models\Role::findByName('admin');
        $role->givePermissionTo('view');
        $role->givePermissionTo('edit');
        $role->givePermissionTo('create');
        $role->givePermissionTo('delete');
        $role->givePermissionTo('approve');
        $role->givePermissionTo('user_management');
        $this->info('Permissions assigned to admin role');
        

    }
}
