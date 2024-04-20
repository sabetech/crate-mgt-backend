<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Constants\EmptiesConstants;

class UpdateEmptiesOnGround
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
        Log::info("Updating Empties on Ground After Shipping to GGBL >>>");

        $emptiesReturningProductLog = $event->emptiesReturningProductLogs;
        Log::info($emptiesReturningProductLog);

        switch($event->action) {
            case EmptiesConstants::RETURNING_EMPTIES_TO_GGBL:
                $this->reduceEmptiesOnGround($emptiesReturningProductLog);
                break;
        }

    }

    public function reduceEmptiesOnGround($model): void
    {
        Log::info("READY TO REDUCE:>>", $model);
    }

}
