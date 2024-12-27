<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Constants\InventoryConstants;
use App\Events\InventoryReceivableCreated;

class InventoryReceivable extends Model
{
    use HasFactory;
    protected $table = 'inventory_receivables';

    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {
            Log::info(["InventoryReceivable created::" => json_encode($model)]);

            event(new InventoryReceivableCreated($model));
        });
    }


    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public static function getReceivableLog($date) {
        $receivableLogs = self::with('product')->where('date', $date)->get();

        return $receivableLogs;
    }

}


