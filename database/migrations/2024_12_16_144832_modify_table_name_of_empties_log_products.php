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
        //
        Schema::table('empties_log_products', function (Blueprint $table) {
            $table->rename('empties_receiving_log_products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('empties_receiving_log_products', function (Blueprint $table) {
            $table->rename('empties_log_products');
        });
    }
};
