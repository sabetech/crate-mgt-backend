<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmptiesOnGroundLog extends Model
{
    use HasFactory;
    protected $table = "empties_on_ground_log";
    protected $guarded = ['id'];

    public function emptiesOnGroundProducts()
    {
        return $this->hasMany(EmptiesOnGroundProduct::class);
    }
}
