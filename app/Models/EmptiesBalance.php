<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class EmptiesBalance extends Model
{
    use HasFactory;

    protected $table = 'empties_balance';
    protected $guarded = ['id'];


    public function product() {
        return $this->belongsTo('\App\Models\Product');
    }

    public function getEmptiesBalance() {
        // $emptyBalances = new stdClass;
        // $empties = EmptiesBalance::with('product')->get();
        // $emptiesInTrade = Customer
        // $emptyBalances->inHouseEmpties = $empties;
        // $emptyBalances->emptiesInTrade =
    }


}
