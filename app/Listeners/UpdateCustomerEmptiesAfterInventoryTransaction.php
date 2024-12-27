<?php

namespace App\Listeners;

use App\Constants\InventoryConstants;
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

        switch($event->activity) {
            case InventoryConstants::SALE_REQUEST:
                $inventoryOrder->order->sales->each(function($sale) use ($inventoryOrder) {
                    $this->updateCustomerEmptiesAccount($inventoryOrder, $sale);
                });
            break;

        }

    }

    public function updateCustomerEmptiesAccount($inventoryOrder, $sale) {

        Log::info($sale->product);

        if (!$sale->product->empty_returnable) return;
        Log::info("Update Customer Empties Account");
        CustomerEmptiesAccount::create([
            'date' => now(),
            'customer_id' => $inventoryOrder->order->customer->id,
            'product_id' => $sale->product_id,
            'quantity_transacted' => $sale->quantity,
            'transaction_type' => 'out',
        ]);
    }
}
