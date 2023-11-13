<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function sales() {
        return $this->hasMany(Sale::class, 'order_id', 'id');
    }

    public function quantity() {
        return $this->hasMany(Sale::class, 'order_id', 'id')->sum('quantity');
    }

    // public function products() {
    //     return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')->withPivot('quantity', 'price', 'total');
    // }

}
