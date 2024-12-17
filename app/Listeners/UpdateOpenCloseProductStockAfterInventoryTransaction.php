<?php

namespace App\Listeners;

use App\Models\OpenCloseProductStock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Constants\InventoryConstants;

class UpdateOpenCloseProductStockAfterInventoryTransaction
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
        $inventoryTransaction = $event->inventoryTransaction;
        $product = $inventoryTransaction->product;

        $openCloseProductStock = OpenCloseProductStock::where('product_id', $product->id)
                                    ->where('date', date("Y-m-d", strtotime($inventoryTransaction->date)))->first();

        if ($openCloseProductStock) {
            if ($inventoryTransaction->activity == InventoryConstants::SALE_REQUEST) {
                $openCloseProductStock->closing_stock -= $inventoryTransaction->quantity;
                $openCloseProductStock->save();
            }
        }else{
            $latestOpenCloseProductStock = OpenCloseProductStock::where('product_id', $product->id)->latest()->first();

            if ($latestOpenCloseProductStock) {
                OpenCloseProductStock::create([
                    'date' => date("Y-m-d", strtotime($inventoryTransaction->date)),
                    'product_id' => $product->id,
                    'opening_stock' => $latestOpenCloseProductStock->closing_stock,
                    'closing_stock' => $latestOpenCloseProductStock->closing_stock + $inventoryTransaction->quantity,
                ]);
            }
        }
    }
}
