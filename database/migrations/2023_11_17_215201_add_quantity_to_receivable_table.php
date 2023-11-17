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
        Schema::table('inventory_receivables', function (Blueprint $table) {
            //
            $table->integer('quantity')->after('product_id')->default(0);
            $table->integer('breakages')->after('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receivable', function (Blueprint $table) {
            //
        });
    }
};
