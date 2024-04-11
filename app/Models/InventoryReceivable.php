<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class InventoryReceivable extends Model
{
    use HasFactory;
    protected $table = 'inventory_receivables';

    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {
            Log::info("Inventory Receivable created");
            Log::info($model);

            //update inventory balance and Empties database.
            self::updateInventoryBalance($model);

        });

        static::updated(function ($model) {
            Log::info("Inventory Receivable Updated");
            self::updateInventoryBalance($model);
        });
    }


    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    static function updateInventoryBalance($model) {
        //update inventory balance
        $inventoryBalance = InventoryBalance::where('product_id', $model->product_id)->first();
        if ($inventoryBalance == null) {

            $inventoryBalance = new InventoryBalance();
            $inventoryBalance->product_id = $model->product_id;
            $inventoryBalance->quantity = $model->quantity;
            $inventoryBalance->save();

        } else {

            $inventoryBalance->quantity += $model->quantity;
            $inventoryBalance->save();

        }
    }
}
