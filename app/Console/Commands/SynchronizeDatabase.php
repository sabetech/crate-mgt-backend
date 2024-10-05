<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SynchronizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:synchronize_database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare databases and synchronize latest updates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /* Not that this is going to be a casually difficult task
        * 1. Scan Databases and Determine which is Current
        *       - Check every table in the local database.
                - Get an array of updated_at dates.
                - Check every remote table in the remote database.
                - Get an array of updated_at dates.
                - Compare dates to determine which database is current
         2. Starting from the top Table, create an exemption of tables
                - 
        */
    }
}
