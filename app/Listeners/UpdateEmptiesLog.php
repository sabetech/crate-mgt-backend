<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\EmptiesReceivingLog;
use App\Models\EmptiesLogProduct;
use App\Models\EmptiesOnGroundLog;
use App\Models\EmptiesOnGroundProduct;
use App\Models\EmptiesBalance;
use App\Models\Product;

class UpdateEmptiesLog
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
        $data = $event->data;
        Log::info($data);

        //get update quantity based on whether these guys are actually empties ...
        $emptiesProductData = [];
        $totalEmptiesQuantity = 0;

        $productsData = json_decode($data['products']);

        foreach ($productsData as $productFromClient) {
            $product = Product::find($productFromClient->product);

            if ($product->empty_returnable) {
                $emptiesProductData[$product->id] = $productFromClient->quantity;
                $totalEmptiesQuantity += $productFromClient->quantity;
            }
        }

        if ($totalEmptiesQuantity === 0) return;

        Log::info($emptiesProductData);
        Log::info("LETS Save empties recievign log");
        Log::info($data);

        $emptiesReceivingLog = EmptiesReceivingLog::updateOrCreate(
            [
                'date' => $data['date'],
                'purchase_order_number' => $data['purchase_order_id']
            ],
            [
                'quantity_received' => $totalEmptiesQuantity,
                'number_of_pallets' => $data['pallets_number'],
                'image_reference' => $data['imageUrl'],
                'vehicle_number' => $data['vehicle_number'],
                'delivered_by' => $data['delivered_by'],
                'received_by' => $data['received_by']
            ]
        );

        foreach ($emptiesProductData as $productId => $quantity) {

            EmptiesLogProduct::updateOrCreate([
                'product_id' => $productId,
                'empties_log_id' => $emptiesReceivingLog->id
            ],
            [
                'quantity' => $quantity
            ]);
        }

        $emptiesOnGroundLog = new EmptiesOnGroundLog;
        $emptiesOnGroundLog->date = $data['date'];
        $emptiesOnGroundLog->quantity = $totalEmptiesQuantity;
        $emptiesOnGroundLog->save();

        foreach ($emptiesProductData as $productId => $quantity) {
            EmptiesOnGroundProduct::updateOrCreate([
                'date' => $data['date'],
                'empties_on_ground_log_id' => $emptiesOnGroundLog->id,
                'product_id' => $productId,
            ],
            [
                'quantity' => $quantity,
            ]);

            $emptiesBalance = EmptiesBalance::where('product_id', $productId)->first();
            if ($emptiesBalance) {
                $emptiesBalance->quantity += $quantity;
                $emptiesBalance->save();
            }else {
                EmptiesBalance::create([
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }
        }
    }
}
