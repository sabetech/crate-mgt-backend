<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\CustomerReturnEmpties;

class CustomerEmptiesAccount extends Model
{
    use HasFactory;
    protected $table = "customer_empties_account";
    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {
            //customer empties account Created ...
            event(new CustomerReturnEmpties($model));
        });

        static::updated(function ($model) {

        });
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function products() {
        return $this->hasManyThrough(Product::class, CustomerEmptiesAccount::class, 'product_id', 'id', 'id', 'customer_id');
    }


}
