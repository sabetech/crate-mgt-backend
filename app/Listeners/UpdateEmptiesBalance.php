<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Model\EmptiesBalance;

class UpdateEmptiesBalance
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
        $emptiesEvent = $event->emptiesBalance;

        Log::info($emptiesEvent);

        EmptiesBalance::updateOrCreate([
            'product_id' => $emptiesEvent['product_id']
        ],
        [
            'quantity' => $emptiesEvent['quantity']
        ]);
    }
}
