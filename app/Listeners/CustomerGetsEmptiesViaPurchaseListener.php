<?php

namespace App\Listeners;

use App\Models\EmptiesTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Constants\EmptiesConstants;

class CustomerGetsEmptiesViaPurchaseListener
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
        EmptiesTransaction::create([
            'datetime' => now(),
            'transaction_id' => "OPK-EMPT-".date("YmdHis"),
            'product_id' => $event->product->id,
            'quantity' => $event->quantity,
            'transaction_type' => 'out',
            'activity' => EmptiesConstants::CUSTOMER_PURCHASE,
        ]);
    }
}
