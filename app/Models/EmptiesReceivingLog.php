<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmptiesReceivingLog extends Model
{
    use HasFactory;
    protected $table = "empties_receiving_log";

    protected $fillable = [
        'date',
        'product_id',
        'quantity_received',
        'vehicle_number',
        'purchase_order_number',
        'received_by',
        'delivered_by',
        'image_reference',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
