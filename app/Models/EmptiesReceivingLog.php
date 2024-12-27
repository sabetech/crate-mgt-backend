<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmptiesReceivingLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "empties_receiving_log";

    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'empties_receiving_log_products', 'empties_log_id', 'product_id')->withPivot('quantity');
    }
}
