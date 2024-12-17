<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Constants\InventoryConstants;

class CustomerGetsEmptiesViaPurchase
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $action;
    public $customerEmptiesAccountInfo;

    /**
     * Create a new event instance.
     */
    public function __construct($customerEmptiesAccountInfo)
    {
        //
        $this->customerEmptiesAccountInfo = $customerEmptiesAccountInfo;
        $this->action = InventoryConstants::SALE_REQUEST;


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
