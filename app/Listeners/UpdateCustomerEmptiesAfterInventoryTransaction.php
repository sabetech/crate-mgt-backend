<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CustomerEmptiesAccount;
use Illuminate\Support\Facades\Log;

class UpdateCustomerEmptiesAfterInventoryTransaction
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
        $inventoryOrder = $event->inventoryOrder;

        Log::info("Inventory Order....");
        Log::info($inventoryOrder);
        
        $inventoryOrder->order->sales->each(function($sale) use ($inventoryOrder) {
            $this->updateCustomerEmptiesAccount($inventoryOrder, $sale);
        });
    }

    public function updateCustomerEmptiesAccount($inventoryOrder, $sale) {
        CustomerEmptiesAccount::create([
            'date' => now(),
            'customer_id' => $inventoryOrder->order->customer->id,
            'product_id' => $sale->product_id,
            'quantity_transacted' => -$inventoryOrder->quantity,
            'transaction_type' => 'out',
        ]);
    }
}
