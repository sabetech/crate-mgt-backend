<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\EmptiesTransaction;
use App\Constants\EmptiesConstants;
use App\Constants\InventoryConstants;

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

        switch($event->activity) {
            case EmptiesConstants::CUSTOMER_RETURN_EMPTIES:
                $this->saveEmptiesTransactionForCustomerEmptiesReturned($event->customerEmptiesAccountEntry);
                break;
            case EmptiesConstants::CUSTOMER_PURCHASE:
                // $this->saveEmptiesTransactionForCustomerPurchase($event->customerEmptiesAccountEntry); Dont need this because customer purchase doesn't reduce empties
                break;
            case InventoryConstants::PURCHASE_ORDER:
                $this->saveEmptiesTransactionForProductsReceivedFromGGBL($event->inventoryReceivable);
                break;
            case EmptiesConstants::RETURNING_EMPTIES_TO_GGBL:
                $this->saveEmptiesTransactionForReturnEmptiesToGGBL($event->emptiesReturningProductLog);
                break;
        }
    }

    public function saveEmptiesTransactionForCustomerEmptiesReturned($model) {

        Log::Debug("Creating Empty Transaction :::", [$model]);
        if (!$model->product->empty_returnable) {
            Log::Info("Product is not empty returnable");
            return;
        }

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

        if (!$model->product->empty_returnable) {
            Log::Info("Product is not empty returnable");
            return;
        }

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

    public function saveEmptiesTransactionForProductsReceivedFromGGBL($model) {

        Log::Debug("Creating Empty Transaction :::", [$model]);
        if (!$model->product->empty_returnable) {
            Log::Info("Product is not empty returnable");
            return;
        }

        EmptiesTransaction::create([
            'datetime' => now(),
            'transaction_id' => "OPK-PROD-REC-".date("YmdHis"),
            'product_id' => $model->product->id,
            'quantity' => $model->quantity,
            'transaction_type' => 'in',
            'activity' => EmptiesConstants::RECEIVING_EMPTIES_FROM_GGBL,
        ]);
    }

    public function saveEmptiesTransactionForReturnEmptiesToGGBL($model) {

        Log::Debug("Creating Empty Transaction :::", [$model]);
        if (!$model->product->empty_returnable) {
            Log::Info("Product is not empty returnable");
            return;
        }

        EmptiesTransaction::create([
            'datetime' => now(),
            'transaction_id' => "OPK-PROD-RET-".date("YmdHis"),
            'product_id' => $model->product->id,
            'quantity' => $model->quantity,
            'transaction_type' => 'out',
            'activity' => EmptiesConstants::RETURNING_EMPTIES_TO_GGBL,
        ]);
    }

}
