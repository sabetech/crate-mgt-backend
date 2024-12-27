<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Constants\EmptiesConstants;
use App\Models\EmptiesLogProduct;
use App\Models\EmptiesReceivingLog;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class UpdateEmptiesReceivingLogProductsOnEmptiesTransactionCreated
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
        if ($event->emptyTransaction->activity !== EmptiesConstants::RECEIVING_EMPTIES_FROM_GGBL) return;

        Log::Debug("REALLY DEBUG::", [$event->emptyTransaction]);



        /*
        $data = $event->data;
        Log::info(["Update Empties Log" => $data]);

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
        Log::info("Saving Empties Receiving Log ...");
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
        */
        // $emptiesOnGroundLog = new EmptiesOnGroundLog;
        // $emptiesOnGroundLog->date = $data['date'];
        // $emptiesOnGroundLog->quantity = $totalEmptiesQuantity;
        // $emptiesOnGroundLog->save();

        // foreach ($emptiesProductData as $productId => $quantity) {
        //     EmptiesOnGroundProduct::updateOrCreate([
        //         'date' => $data['date'],
        //         'empties_on_ground_log_id' => $emptiesOnGroundLog->id,
        //         'product_id' => $productId,
        //     ],
        //     [
        //         'quantity' => $quantity,
        //     ]);
        // }
    }
}
