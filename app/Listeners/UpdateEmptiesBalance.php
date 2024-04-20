<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\EmptiesBalance;
use App\Constants\EmptiesConstants;


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
        $model = $event->emptyTransaction;

        //modify empties balances here ...
        $existingBalance = EmptiesBalance::where('product_id', $model->product_id)->first();
        $finalQuantity = 0;

        if ($existingBalance) {
            if ($model->transaction_type === 'in') {
                $finalQuantity = $existingBalance->quantity + $model->quantity;
            }else{
                $finalQuantity = $existingBalance->quantity - $model->quantity;
            }
        }else {
            $finalQuantity = $model->quantity;
        }

        if ($model->activity === EmptiesConstants::CUSTOMER_PURCHASE) {

        }

        EmptiesBalance::updateOrCreate([
            'product_id' => $model->product_id
        ],[
            'quantity' => $finalQuantity
        ]);
    }
}
