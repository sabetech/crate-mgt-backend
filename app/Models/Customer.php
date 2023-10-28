<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = "customers";

    public function customerEmptiesAccount(){
        return $this->hasMany(CustomerEmptiesAccount::class);
    }

    public function vseLoadout() {
        return $this->hasMany(Loadout::class);
    }
}
