<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\CustomerEmptiesAccount;
use Log;

class CustomerImport implements ToCollection
{
    /**
    * @param array $row
    *
    */
    public function collection(Collection $rows)
    {

        Log::info("Importing customers", ["rows" => $rows]);

        foreach ($rows as $key => $row)
        {
            //skip first row
            if ($key == 0)
                continue;


            Log::info("Importing customer: name" . $row[0]);
            Log::info("Importing customer: phone" . $row[1]);
            Log::info("Importing customer: customer_type" . $row[2]);
            $customer = Customer::create([
                'name' => $row[0],
                'phone' => $row[1],
                'customer_type' => $row[2],
            ]);

            CustomerEmptiesAccount::create([
                'customer_id' => $customer->id,
                'product_id' => 1,
                'quantity_transacted' => $row[3],
                'date' => date('Y-m-d'),
                'transaction_type' => "in"
            ]);
        }
    }
}
