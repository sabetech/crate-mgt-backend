<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LoadoutProduct extends Model
{
    use HasFactory;
    protected $table = 'loadout_products';
    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {
            //update inventory when loadout is created
            Log::info("Loadout Products created >>>");
            self::updateInventoryBalance($model);
        });

        static::updated(function ($model) {
            //find a way to undo loadout and it's effects ...
            Log::info("Loadout Products Updated >>>");
        });
    }

    static function updateInventoryBalance($model) {
        $inventoryBalance = InventoryBalance::where('product_id', $model->product_id)->first();
        if ($inventoryBalance == null) {
           return;
        } else {
            $inventoryBalance->quantity -= $model->quantity;
            $inventoryBalance->save();

        }
        // $product = Product::find($model->product_id);
        // $product->inventory_balance = $product->inventory_balance - $model->quantity;
        // $product->save();
        // return $product;
    }


    public function customer(){
        return $this->hasOne('App\Models\Customer','id','customer_id');
    }

    public function product(){
        return $this->hasOne('App\Models\Product','id','product_id');
    }

}
