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

        Log::info('UpdateProductBalanceAfterInventoryTransaction');
        Log::info($inventoryTransaction);
        Log::info($inventoryTransaction->product);
        $product = $inventoryTransaction->product;
        $product->inventoryBalance->quantity = $inventoryTransaction->balance;
        $product->inventoryBalance->save();

    }
}
