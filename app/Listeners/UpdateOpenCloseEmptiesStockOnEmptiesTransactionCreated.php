<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\OpenCloseEmptiesStock;
use Illuminate\Support\Facades\Log;

class UpdateOpenCloseEmptiesStockOnEmptiesTransactionCreated
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
        $emptiesOpenCloseStock = OpenCloseEmptiesStock::where('date', date("Y-m-d", strtotime($event->emptyTransaction->datetime)))
            ->where('product_id', $event->emptyTransaction->product_id)
            ->first();

        if ($emptiesOpenCloseStock) {
            Log::Info("Existing Empties OPen Closing Stock", [$emptiesOpenCloseStock]);
            if ($event->emptyTransaction->transaction_type == 'in') {
                $emptiesOpenCloseStock->closing_stock += $event->emptyTransaction->quantity;
            }else{
                $emptiesOpenCloseStock->closing_stock -= $event->emptyTransaction->quantity;
            }

            $emptiesOpenCloseStock->save();

        }else{
            $latestProductEmptyStock = OpenCloseEmptiesStock::where('product_id', $event->emptyTransaction->product_id)->latest()->first();

            if ($latestProductEmptyStock) {

                OpenCloseEmptiesStock::create([
                    'date' => date("Y-m-d", strtotime($event->emptyTransaction->datetime)),
                    'product_id' => $event->emptyTransaction->product_id,
                    'opening_stock' => $latestProductEmptyStock->closing_stock,
                    'closing_stock' => ($event->emptyTransaction->transaction_type == 'in') ? ($latestProductEmptyStock->closing_stock + $event->emptyTransaction->quantity) : ($latestProductEmptyStock->closing_stock - $event->emptyTransaction->quantity),
                ]);
            }
        }
    }
}
