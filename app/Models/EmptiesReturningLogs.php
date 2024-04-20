<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmptiesReturningLogs extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'empties_returning_logs_products', 'empties_returning_log_id', 'product_id')->withPivot('quantity');
    }

}
