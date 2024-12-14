<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\InventoryTransactionCreated;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $table = 'inventory_transactions';
    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {
            Log::info(["InventoryTransaction created::" => json_encode($model)]);

            event(new InventoryTransactionCreated($model));
        });
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public static function getInventoryByDate($date) {
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        return self::whereBetween('date', [$startOfDay, $endOfDay])->with('product')->orderBy('date', 'asc')->get();
    }
}
