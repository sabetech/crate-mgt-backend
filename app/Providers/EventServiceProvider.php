<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\CustomerEmptiesAccountEntryCreated;
use App\Events\InventoryOrderApproved;
use App\Events\InventoryTransactionCreated;
use App\Events\StockTakenForProduct;
use App\Events\InventoryReceivedFromGBL;
use App\Events\ReceivedProductFromGGBL;
use App\Events\CustomerGetsEmptiesViaPurchase;
use App\Events\LoadoutProductCreated;
use App\Events\ReturnProductToGGBL;
use App\Events\EmptiesTransactionCreated;
use App\Events\CustomerReturnEmpties;
use App\Listeners\UpdateEmptiesTransactionTable;
use App\Listeners\UpdateInventoryPendingOrders;
use App\Listeners\UpdateInventoryTransactions;
use App\Listeners\UpdateCustomerEmptiesAfterInventoryTransaction;
use App\Listeners\UpdateProductBalanceAfterStockTaken;
use App\Listeners\UpdateProductBalanceAfterInventoryTransaction;
use App\Listeners\UpdateEmptiesLog;
use App\Listeners\UpdateEmptiesTransaction;
use App\Listeners\UpdateEmptiesReceivingLogProductsOnEmptiesTransactionCreated;
use App\Listeners\UpdateEmptiesBalanceOnEmptiesTransactionCreated;
use App\Listeners\UpdateEmptiesReturnLogProductsOnEmptiesTransactionCreated;
use App\Listeners\UpdateOpenCloseEmptiesStockOnEmptiesTransactionCreated;
use App\Models\EmptiesTransaction;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        CustomerEmptiesAccountEntryCreated::class => [
            UpdateEmptiesTransactionTable::class,
        ],

        EmptiesTransactionCreated::class => [
            //These are conditional listeners
            UpdateEmptiesReturnLogProductsOnEmptiesTransactionCreated::class,
            UpdateEmptiesReceivingLogProductsOnEmptiesTransactionCreated::class,

            //these are always run
            UpdateOpenCloseEmptiesStockOnEmptiesTransactionCreated::class,
            UpdateEmptiesBalanceOnEmptiesTransactionCreated::class,
        ],






    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
