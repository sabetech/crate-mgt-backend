<?php

namespace App\Models;

use App\Events\PromoStockCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PromoStock extends Model
{
    use HasFactory;

    protected $table = 'promo_stocks';

    protected $fillable = [
        'product_id',
        'date',
        'quantity',
    ];

    //write an event listener for every update or insert
    //on promo_stock table
    protected static function booted() {
        static::created(function ($model) {
            //update inventory when promo stock is created
            Log::info("Promo Stock created >>>" );
            event(new PromoStockCreated($model));
        });

        static::updated(function ($model) {
            Log::info("Promo Stock updated >>>");
            event(new PromoStockCreated($model));
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
