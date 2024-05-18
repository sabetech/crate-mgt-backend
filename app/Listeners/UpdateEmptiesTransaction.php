<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Constants\EmptiesConstants;
use App\Constants\InventoryConstants;
use App\Models\EmptiesTransaction;
use App\Models\CustomerEmptiesAccount;

class UpdateEmptiesTransaction
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

        switch($event->action) {
            case EmptiesConstants::RETURNING_EMPTIES_TO_GGBL:
                Log::info("Updating Empties on Ground After Shipping to GGBL >>>");
                $emptiesReturningProductLog = $event->emptiesReturningProductLog;
                $this->updateEmptiesTransactionOnEmptiesReturned($emptiesReturningProductLog);
                break;
            case EmptiesConstants::CUSTOMER_RETURN_EMPTIES:
                // dd($event->customerEmptiesAccount);
                $customerEmptiesAccount = $event->customerEmptiesAccount;
                $this->updateEmptiesTransactionOnCustomerReturned($customerEmptiesAccount);
                break;
            case InventoryConstants::SALE_REQUEST:
                $saleOrder = $event->inventoryOrder;
                $this->updateEmptiesTransactionOnCustomerPurchase($saleOrder);
                break;
        }
    }

    public function updateEmptiesTransactionOnEmptiesReturned($model): void
    {
        Log::info("Update Empties Transaction");
        Log::info($model);

        $emptiesTransaction = new EmptiesTransaction;
        $emptiesTransaction->transaction_id = "OPK-EMPT-".date("YmdHis");
        $emptiesTransaction->datetime = date("Y-m-d H:i:s");
        $emptiesTransaction->product_id = $model->product_id;
        $emptiesTransaction->quantity = $model->quantity;
        $emptiesTransaction->transaction_type = 'out';

        $emptiesTransaction->activity = EmptiesConstants::RETURNING_EMPTIES_TO_GGBL;

        $emptiesTransaction->save();
    }

    public function updateEmptiesTransactionOnCustomerReturned($model): void {
        $emptiesTransaction = new EmptiesTransaction;
        $emptiesTransaction->transaction_id = "OPK-EMPT-".date("YmdHis");
        $emptiesTransaction->datetime = date("Y-m-d H:i:s");
        $emptiesTransaction->product_id = $model->product_id;
        $emptiesTransaction->quantity = $model->quantity_transacted;
        $emptiesTransaction->transaction_type = $model->transaction_type;
        $emptiesTransaction->activity = EmptiesConstants::CUSTOMER_RETURN_EMPTIES;

        $emptiesTransaction->save();
    }

    public function updateEmptiesTransactionOnCustomerPurchase($model): void {
        $order = $model->order;

        foreach($order->sales as $sale) {

            $emptiesTransaction = new EmptiesTransaction;
            $emptiesTransaction->transaction_id = $order->transaction_id;
            $emptiesTransaction->datetime = date("Y-m-d H:i:s");
            $emptiesTransaction->product_id = $sale->product_id;
            $emptiesTransaction->quantity = $sale->quantity;
            $emptiesTransaction->transaction_type = 'out';
            $emptiesTransaction->activity = EmptiesConstants::CUSTOMER_PURCHASE;

            $emptiesTransaction->save();
        }

        $order->sales->each(function($sale) use ($order) {
            $this->updateCustomerEmptiesAccount($order, $sale);
        });

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
