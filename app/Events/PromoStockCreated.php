<?php

namespace App\Events;

use App\Models\PromoStock;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Constants\InventoryConstants;

class PromoStockCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $promoStockDisbursement, $activity;
    /**
     * Create a new event instance.
     */
    public function __construct($promoStockProduct)
    {
        //
        $this->promoStockDisbursement = $promoStockProduct;
        $this->activity = InventoryConstants::PROMO_STOCK_DISBURSEMENT;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
