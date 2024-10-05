<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerEmptiesAccount;
use App\Models\LoadoutProduct;

class Customer extends Model
{
    use HasFactory;
    protected $table = "customers";
    protected $guarded = ['id'];

    public function customerEmptiesAccount(){
        return $this->hasMany(CustomerEmptiesAccount::class);
    }

    public function vseLoadout() {
        return $this->hasMany(LoadoutProduct::class);
    }


}
