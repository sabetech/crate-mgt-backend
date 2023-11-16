<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Log;

class UpdateInventoryTransactionsAfterOrderApproval
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
        $action = $event->action;
        switch($action) {
            case 'sale_request':
                $this->updateInventoryAfterOrderApproval($event->inventoryOrder, $action);
            break;
            case 'approved_purchase_order':
            
            break;
            case 'return_in_by_vse':
            
                break;
            case 'loadout_by_vse':
            
                break;

            case 'record_breakages': //decide whether or not to remove from main inventory
            
                break;
        }

        
    }

    public function updateInventoryAfterOrderApproval($inventoryOrder, $action) {
        $order = $inventoryOrder->order;
        Log::info('updateInventoryAfterOrderApproval');
        Log::info($order);
        Log::info($order->sales);

        foreach($order->sales as $sale) {
            
            $previousBalance = $sale->product->inventoryBalance->quantity;

            InventoryTransaction::create([
                'date' => now(),
                'product_id' => $sale->product_id,
                'activity' => $action,
                'comment' => 'Order #' . $order->transaction_id . ' approved',
                'quantity' => -$sale->quantity,
                'balance' => $previousBalance - $sale->quantity,
                'user_id' => $order->user_id
            ]);
        }

        //other events should be
        // - update live inventory 
        // - update customer empties

                
       
    }

}
