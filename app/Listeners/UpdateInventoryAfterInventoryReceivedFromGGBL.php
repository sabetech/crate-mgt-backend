<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\InventoryTransaction;
use App\Constants\InventoryConstants;
use Illuminate\Support\Facades\Auth;

class UpdateInventoryAfterInventoryReceivedFromGGBL
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
        $user = Auth::user();
        $data = $event->data;
        Log::info(["Data to Update Inventory transaction with" => $data]);
        $productsData = json_decode($data['products']);

        foreach ($productsData as $productFromClient) {
            $product = Product::find($productFromClient->product);

            $previousBalance = 0;

            if ($product) {

                if ($product->inventoryBalance) {
                    $previousBalance = $product->inventoryBalance->quantity;
                }

                $inventoryTransaction = new InventoryTransaction;
                $inventoryTransaction->product_id = $product->id;
                $inventoryTransaction->date = date("Y-m-d H:i:s");
                $inventoryTransaction->activity = InventoryConstants::PURCHASE_ORDER;
                $inventoryTransaction->comment = "A truck came and gave stuff. Update with something sensible!";
                $inventoryTransaction->quantity = $productFromClient->quantity;
                $inventoryTransaction->balance = $previousBalance + $productFromClient->quantity;
                $inventoryTransaction->user_id = $user->id;
                $inventoryTransaction->save();
            }

        }

    }
}
