<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class ReadProductsFromCSVandSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:read_products_csv_and_save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command reads the CSV file and saves the data to the products table database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Reading the CSV file and saving the data to the products table database.');
        $this->info('=========================================================================');
        $this->info('Reading... '.__DIR__.'/products.csv');
        $file = fopen(__DIR__.'/products.csv', 'r');
        $header = fgetcsv($file);
        $rows = [];
        while ($row = fgetcsv($file)) {
            $rows[] = array_combine($header, $row);

            Product::updateOrCreate(['sku_name' => $row[0]],[
                'sku_name' => $row[0],
                'sku_code' => $row[2],
                'retail_price' => $row[4],
                'wholesale_price' => $row[3],
                'empty_returnable' => $row[1] === 'Returnable' ? 1 : 0,
            ]);
        }

        $this->info('Products saved successfully.');

        fclose($file);    
    }
}
