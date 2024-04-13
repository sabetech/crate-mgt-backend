<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\EmptiesReceivingLog;

class UpdateEmptiesLog
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
        $data = $event->data;
        Log::info($data);
        $emptiesReceivingLog = EmptiesReceivingLog::updateOrCreate(
            [
                'date' => $data['date'],
                'purchase_order_number' => $data['purchase_order_id']
            ],
            [
                'quantity_received' => $data['quantity_received'],
                'number_of_pallets' => $data['pallets_number'],
                'image_reference' => $data['imageUrl'],
                'vehicle_number' => $data['vehicle_number'],
                'delivered_by' => $data['delivered_by'],
                'received_by' => $data['received_by']
            ]
        );

        Log::info("LETS see what empties log is");
        Log::info($emptiesReceivingLog);

        foreach ($data['products'] as $product) {

            EmptiesLogProduct::updateOrCreate([
                'product_id' => $product->id,
                'empties_log_id' => $emptiesReceivingLog->id
            ],
            [
                'quantity' => $product->quantity
            ]);
        }

    }
}
