<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\EmptiesTransactionCreated;

class EmptiesTransaction extends Model
{
    use HasFactory;
    protected $table = 'empties_transactions';
    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {

            event(new EmptiesTransactionCreated($model));

        });
    }



    public function product() {
        return $this->belongsTo('\App\Models\Product');
    }


}
