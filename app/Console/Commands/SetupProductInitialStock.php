<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\InventoryBalance;

class SetupProductInitialStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // receive a file path as an argument
    protected $signature = 'app:setup-product-initial-stock {file_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a command that sets the initial stock of products in the inventory_balance table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // get the file path from the argument
        $file_path = $this->argument('file_path');

        // check if the file exists
        if (!file_exists($file_path)) {
            $this->error('The file does not exist');
            return;
        }

        //read the csv file
        $file = fopen($file_path, 'r');
        $data = fgetcsv($file);

        // loop through the file and set the initial stock
        while (($data = fgetcsv($file)) !== false) {
            $product_id = $data[0];
            $initial_stock = $data[5];

            // check if the product exists


            Product::create(['sku_name' => $data[1],
                                'sku_code' => $data[2],
                                'retail_price' => $data[3],
                                'wholesale_price' => $data[4],
                                'empty_returnable' => $data[6],
                            ]);
            $this->info('Product ' . $data[1] . ' has been created successfully');

            // check if the initial stock is a number
            if (!is_numeric($initial_stock)) {
                $this->error('Initial stock for product with id ' . $product_id . ' is not a number');
                continue;
            }

            // check if the initial stock is greater than or equal to 0
            if ($initial_stock < 0) {
                $this->error('Initial stock for product with id ' . $product_id . ' is less than 0');
                continue;
            }

            // check if the product already has an initial stock
            $inventory_balance = InventoryBalance::where('product_id', $product_id)->first();
            if ($inventory_balance) {
                $inventory_balance->quantity = $initial_stock;
                continue;
            }

            // set the initial stock
            InventoryBalance::create([
                'product_id' => $product_id,
                'quantity' => $initial_stock,
                'breakages' => 0,
            ]);

            $this->info('Initial stock for product ' . $data[1] . ' ' . $data[5] .' has been set successfully');

        }

        $this->info('Initial stock has been set successfully');

    }
}
