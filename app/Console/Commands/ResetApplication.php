<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\confirm;


class ResetApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset_application';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command resets the applications test data and environment. Use with Caution. Make sure you have back ups of your data as Data cannot be restored after this!';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $confirmed = $this->confirm('Are you sure you want to reset the application? This will delete all data and cannot be undone. Type "yes" to continue.');
        
        $this->info('Resetting the application...');
        $this->info('============================');

        $this->info('Resetting the database...');

        $this->call('migrate:fresh');
        $this->call('app:read_products_csv_and_save');
        $this->call('app:setup-roles-for-users');

        $this->info('Creating New Admin User...');

        $user = User::create([
            'name'=> 'admin',
            'email'=> 'admin@opk.com',
            'password'=> bcrypt('testify_app')
        ]);

        $user->assignRole('admin');        
    }
}
