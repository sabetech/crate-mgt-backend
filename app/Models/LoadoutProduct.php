<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoadoutProduct extends Model
{
    use HasFactory;
    protected $table = 'loadout_products';
    protected $guarded = ['id'];

    public function customer(){
        return $this->hasOne('App\Models\Customer','id','customer_id');
    }

    public function product(){
        return $this->hasOne('App\Models\Product','id','product_id');
    }

}
