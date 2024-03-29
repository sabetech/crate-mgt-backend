<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $guarded = ['id'];

    public function stocks() {
        return $this->belongsTo(Stock::class, 'id', 'product_id');
    }

    public function inventoryBalance() {
        return $this->belongsTo(InventoryBalance::class, 'id', 'product_id');
    }
}
