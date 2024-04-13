<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\EmptiesReceivingLog;
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

        foreach ($productsData as $product) {
            $product = Product::find($product->id);

            if ($product->empty_returnable) {
                $emptiesProductData[$product->id] = $product->quantity;
                $totalEmptiesQuantity += $product->quantity;
            }
        }

        if ($totalEmptiesQuantity === 0) return;

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

        Log::info("LETS see what empties log is");
        Log::info($emptiesReceivingLog);

        foreach ($emptiesProductData as $productId => $quantity) {

            EmptiesLogProduct::updateOrCreate([
                'product_id' => $productId,
                'empties_log_id' => $emptiesReceivingLog->id
            ],
            [
                'quantity' => $quantity
            ]);
        }

    }
}
