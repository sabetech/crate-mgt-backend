<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empties_on_ground_products', function (Blueprint $table) {
            //
            $table->boolean('is_empty')->after('quantity')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empties_on_ground_product', function (Blueprint $table) {
            //
        });
    }
};
