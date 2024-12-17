<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\InventoryTransaction;
use App\Constants\InventoryConstants;
use Illuminate\Support\Facades\Log;
use Auth;

class UpdateInventoryTransactions
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
            case InventoryConstants::SALE_REQUEST:
                $this->updateInventoryAfterOrderApproval($event->inventoryOrder, $action);
            break;
            case InventoryConstants::PURCHASE_ORDER:
                $this->updateInventoryTransactionAfterReceivable($event->receivedProduct);
            break;
            case InventoryConstants::LOAD_OUT:
                $this->updateInventoryTransactionAfterLoadout($event->loadoutProduct);
                break;

            default: //other actions

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
                'activity' => InventoryConstants::SALE_REQUEST,
                'comment' => 'Order #' . $order->transaction_id . ' approved',
                'quantity' => -$sale->quantity,
                'balance' => $previousBalance - $sale->quantity,
                'user_id' => $order->user_id
            ]);
        }

    }

    public function updateInventoryTransactionAfterReceivable($model) {
        //update inventory transaction ...
        $user = Auth::user();

        Log::info("product inventory balance", [ $model->product->inventoryBalance ]);

        if ($ib = $model->product->inventoryBalance) {
            $previousBalance = $ib->quantity;
        }else{
            $previousBalance = 0;
        }

        Log::info(["updateInventoryTransactionAfterReceivable" => $model]);

        Log::info("PREVIOUS BALANCE:::",[$previousBalance]);

        $inventoryTransaction = new InventoryTransaction;
        $inventoryTransaction->product_id = $model->product_id;
        $inventoryTransaction->date = date("Y-m-d H:i:s");
        $inventoryTransaction->activity = InventoryConstants::PURCHASE_ORDER;
        $inventoryTransaction->comment = "A truck came and gave stuff. Update with somethign sensible!";
        $inventoryTransaction->quantity = $model->quantity;
        $inventoryTransaction->balance = $previousBalance + $model->quantity;
        $inventoryTransaction->user_id = $user->id;
        $inventoryTransaction->save();
    }

    public function updateInventoryTransactionAfterLoadout($model) {
        $user = Auth::user();
        Log::info($model);
        if ($ib = $model->product->inventoryBalance) {
            $previousBalance = $ib->quantity;
        }else{
            $previousBalance = 0;
        }

        $customer = $model->customer;

        $inventoryTransaction = new InventoryTransaction;
        $inventoryTransaction->product_id = $model->product_id;
        $inventoryTransaction->date = date("Y-m-d H:i:s");
        $inventoryTransaction->activity = InventoryConstants::LOAD_OUT;
        $inventoryTransaction->comment = "A loadout given to VSE: $customer->name";
        $inventoryTransaction->quantity = $model->quantity;
        $inventoryTransaction->balance = $previousBalance + (-$model->quantity);
        $inventoryTransaction->user_id = $user->id;
        $inventoryTransaction->save();
    }

}
