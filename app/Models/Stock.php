<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table='stock';
    protected $guarded = ['id'];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
