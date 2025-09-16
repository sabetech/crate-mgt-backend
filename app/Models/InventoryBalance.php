<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryBalance extends Model
{
    use HasFactory;

    protected $table = 'inventory_balance';
    protected $guarded = ['id'];

    

}
