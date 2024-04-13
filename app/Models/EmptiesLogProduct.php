<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EmptiesLogProduct extends Model
{
    use HasFactory;
    protected $table='empties_log_products';

    protected $guarded = ['id'];

}
