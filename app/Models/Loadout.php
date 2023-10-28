<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loadout extends Model
{
    use HasFactory;
    protected $table = 'loadout';
    protected $guarded = ['id'];

    public function customer(){
        return $this->hasOne('App\Models\Customer','id','customer_id');
    }

    public function product(){
        return $this->hasOne('App\Models\Product','id','product_id');
    }

}
