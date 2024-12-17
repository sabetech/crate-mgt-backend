<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\CustomerEmptiesAccountEntryCreated;
use App\Constants\EmptiesConstants;

class CustomerEmptiesAccount extends Model
{
    use HasFactory;
    protected $table = "customer_empties_account";
    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {
            if ($model->transaction_type === 'in') {
                $model->activity = EmptiesConstants::CUSTOMER_RETURN_EMPTIES;
                event(new CustomerEmptiesAccountEntryCreated($model));
            }

            if ($model->transaction_type === 'out') {
                $model->activity = EmptiesConstants::CUSTOMER_PURCHASE;
                event(new CustomerEmptiesAccountEntryCreated($model));
            }


        });

        // static::updated(function ($model) {

        // });
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function products() {
        return $this->hasManyThrough(Product::class, CustomerEmptiesAccount::class, 'product_id', 'id', 'id', 'customer_id');
    }


}
