<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\InventoryBalance;
use Illuminate\Support\Facades\Log;

class UpdateProductBalanceAfterInventoryTransaction
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
        $inventoryTransaction = $event->inventoryTransaction;

        Log::info(['Running UpdateProductBalanceAfterInventoryTransaction' => $inventoryTransaction]);

        Log::info(['Product whose balance is being updated' => $inventoryTransaction->product]);
        $product = $inventoryTransaction->product;

        InventoryBalance::updateOrCreate([
            'product_id' => $product->id
        ], [
            'quantity' => $inventoryTransaction->balance,
        ]);
    }
}
