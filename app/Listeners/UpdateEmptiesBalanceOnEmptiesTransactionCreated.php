<?php

namespace App\Listeners;

use App\Models\EmptiesBalance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateEmptiesBalanceOnEmptiesTransactionCreated
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
        // Log::info("Running UpdateEmptiesBalanceOnEmptiesTransactionCreated", [$event]);

        //get the existing balance and update the quantity else save the new balance
        $existingBalance = EmptiesBalance::where('product_id', $event->emptyTransaction->product_id)->first();

        if ($existingBalance) {

            if ($event->emptyTransaction->transaction_type === 'in') {

                $existingBalance->quantity += $event->emptyTransaction->quantity;
            }else{

                $existingBalance->quantity -= $event->emptyTransaction->quantity;
            }
            $existingBalance->save();
            return;
        }else{
            EmptiesBalance::create([
                'product_id' => $event->emptyTransaction->product_id,
                'quantity' => $event->emptyTransaction->quantity,
            ]);
        }
    }
}
