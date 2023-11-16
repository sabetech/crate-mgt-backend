<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CustomerEmptiesAccount;
use Illuminate\Support\Facades\Log;

class UpdateCustomerEmptiesAfterInvenetoryTransaction
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

        Log::info("Inventory Order....");
        Log::info($inventoryTransaction);

        CustomerEmptiesAccount::create([
            'date' => now(),
            'customer_id' => $inventoryTransaction->order->customer->id,
            'product_id' => $inventoryTransaction->product_id,
            'quantity_transacted' => -$inventoryTransaction->quantity,
            'transaction_type' => 'out',
        ]);
    }
}
