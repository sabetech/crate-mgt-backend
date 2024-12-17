<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenCloseProductStock extends Model
{
    use HasFactory;
    protected $table = 'daily_open_close_product_stock';
    protected $guarded = ['id'];
}
