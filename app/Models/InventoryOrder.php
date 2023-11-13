<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryOrder extends Model
{
    use HasFactory;

    protected $table = 'inventory_orders';
    protected $guarded =['id'];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
