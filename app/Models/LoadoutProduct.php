<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Events\LoadoutProductCreated;

class LoadoutProduct extends Model
{
    use HasFactory;
    protected $table = 'loadout_products';
    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {
            //update inventory when loadout is created
            Log::info("Loadout Products created >>>");
            self::updateInventoryTransaction($model);
        });

        static::updated(function ($model) {
            //find a way to undo loadout and it's effects ... ASAP
            self::updateInventoryTransaction($model);
            Log::info("Loadout Products Updated >>>");
        });
    }

    static function updateInventoryTransaction($model) {
        event(new LoadoutProductCreated($model));
    }

    public function customer(){
        return $this->hasOne('App\Models\Customer','id','customer_id');
    }

    public function product(){
        return $this->hasOne('App\Models\Product','id','product_id');
    }

}
