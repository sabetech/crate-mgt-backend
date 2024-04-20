<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\ReceivedProductFromGGBL;
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
            event(new ReceivedProductFromGGBL($model)); //raise an event to update inventory transaction ...

        });

        static::updated(function ($model) {
            Log::info("Inventory Receivable Updated");

            event(new ReceivedProductFromGGBL($model)); //raise an event to update inventory transaction ...

        });
    }


    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
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
        $receivableLogs = self::with('product')->where('date', $date)->get();

        return $receivableLogs;
    }

}


