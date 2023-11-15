<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\InventoryOrder;

class UpdateInventoryPendingOrders
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
        //update the inventory orders table as a pending item
        InventoryOrder::create([
            'datetime' => now(),
            'order_id' => $event->order->id,
            'user_id' => $event->order->user_id,
            'status' => 'pending',
        ]);

    }
}
