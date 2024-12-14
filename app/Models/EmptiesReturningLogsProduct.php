<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\ReturnProductToGGBL;
use Log;

class EmptiesReturningLogsProduct extends Model
{
    use HasFactory;
    protected $table = 'empties_returning_logs_products';
    protected $guarded = ['id'];

    protected static function booted() {
        static::created(function ($model) {
            Log::info("Empties returning created");
            Log::info($model);

            event(new ReturnProductToGGBL($model));
        });

        static::updated(function ($model) {
            Log::info("Empties returning updated");
            Log::info($model);

            event(new ReturnProductToGGBL($model));
        });
    }
}
