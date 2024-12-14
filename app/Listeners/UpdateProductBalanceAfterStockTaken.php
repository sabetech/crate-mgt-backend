<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\InventoryBalance;

class UpdateProductBalanceAfterStockTaken
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
        $stockItem = $event->stockItem;
        $product = $stockItem->product;
        InventoryBalance::updateOrCreate([
            'product_id' => $product->id],[
            'quantity' => $stockItem->quantity,
        ]);

        //Don't do this just yet

    }
}
