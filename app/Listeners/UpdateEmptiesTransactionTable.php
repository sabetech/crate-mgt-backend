<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\EmptiesTransaction;
use App\Constants\EmptiesConstants;

class UpdateEmptiesTransactionTable
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
        Log::Debug(["Event Info" => $event]);

        switch($event->customerEmptiesAccountEntry->activity) {
            case EmptiesConstants::CUSTOMER_RETURN_EMPTIES:
                $this->saveEmptiesTransactionForCustomerEmptiesReturned($event->customerEmptiesAccountEntry);
                break;
            case EmptiesConstants::CUSTOMER_PURCHASE:
                $this->saveEmptiesTransactionForCustomerPurchase($event->customerEmptiesAccountEntry);
                break;
        }



    }

    public function saveEmptiesTransactionForCustomerEmptiesReturned($model) {

        Log::Debug("Creating Empty Transaction :::", [$model]);

        EmptiesTransaction::create([
            'datetime' => now(),
            'transaction_id' => "OPK-CUST-EMPT-RET-".date("YmdHis"),
            'product_id' => $model->product->id,
            'quantity' => $model->quantity_transacted,
            'transaction_type' => $model->transaction_type,
            'activity' => EmptiesConstants::CUSTOMER_RETURN_EMPTIES,
            'customer_id' => $model->customer_id
        ]);
    }

    public function saveEmptiesTransactionForCustomerPurchase($model) {
        EmptiesTransaction::create([
            'datetime' => now(),
            'transaction_id' => "OPK-CUST-PUR-".date("YmdHis"),
            'product_id' => $model->product->id,
            'quantity' => $model->quantity_transacted,
            'transaction_type' => $model->transaction_type,
            'activity' => EmptiesConstants::CUSTOMER_PURCHASE,
            'customer_id' => $model->customer_id
        ]);
    }
}
