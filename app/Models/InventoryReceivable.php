<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Constants\InventoryConstants;

class InventoryReceivable extends Model
{
    use HasFactory;
    protected $table = 'inventory_receivables';

    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {
            Log::info("Inventory Receivable created");
            Log::info($model);

            //update inventory transaction and let inventory transaction update balances
            self::saveInventoryTransaction($model);

        });

        static::updated(function ($model) {
            Log::info("Inventory Receivable Updated");
            self::saveInventoryTransaction($model);

        });
    }


    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    static function saveInventoryTransaction($model) {
        //update inventory transaction ...
        $user = Auth::user();

        $previousTransaction = InventoryTransaction::where('product_id', $model->product_id)
                                ->orderBy('updated_at', 'desc')->first();

        $inventoryTransaction = new InventoryTransaction;
        $inventoryTransaction->product_id = $model->product_id;
        $inventoryTransaction->date = date("Y-m-d H:i:s");
        $inventoryTransaction->activity = InventoryConstants::PURCHASE_ORDER;
        $inventoryTransaction->comment = "A truck came and gave stuff. Update with somethign sensible!";
        $inventoryTransaction->quantity = $model->quantity;
        $inventoryTransaction->balance = (isset($previousTransaction->balance)) ? $previousTransaction->balance + $model->quantity : $model->quantity;
        $inventoryTransaction->user_id = $user->id;
        $inventoryTransaction->save();
    }


    // DO NOT ENABLE THIS UNDER ANY CIRCUMSTANCE. I WAS IN MY RIGHT MIND WHEN I SAID THIS
    // static function updateInventoryBalance($model) {
    //     //update inventory balance
    //     $inventoryBalance = InventoryBalance::where('product_id', $model->product_id)->first();
    //     if ($inventoryBalance == null) {

    //         $inventoryBalance = new InventoryBalance();
    //         $inventoryBalance->product_id = $model->product_id;
    //         $inventoryBalance->quantity = $model->quantity;
    //         $inventoryBalance->save();

    //     } else {

    //         $inventoryBalance->quantity += $model->quantity;
    //         $inventoryBalance->save();

    //     }
    // }

    public static function getReceivableLog($date) {
        $receivableLogs = self::where('date', $date)->get();

        return $receivableLogs;
    }

}


