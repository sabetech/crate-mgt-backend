<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Events\StockTakenForProduct;

class Stock extends Model
{
    use HasFactory;
    protected $table='stock';
    protected $guarded = ['id'];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    protected static function booted() {
        static::created(function ($model) {
            Log::info("Stock Taken for Product");
            Log::info($model);

            //dispatch event here
            event(new StockTakenForProduct($model));
        });

        static::updated(function ($model) {
            Log::info("Stock Updated for Product");
            Log::info($model);

            //dispatch event here
            event(new StockTakenForProduct($model));
        });
    }

}
