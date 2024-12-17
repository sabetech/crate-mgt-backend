<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenCloseEmptiesStock extends Model
{
    use HasFactory;

    protected $table = 'daily_open_close_empties_stock';
    protected $guarded = ['id'];

}
