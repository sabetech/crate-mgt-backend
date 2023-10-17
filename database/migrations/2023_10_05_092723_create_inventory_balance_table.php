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
        Schema::create('inventory_balance', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('product_id')->references('id')->on('products');
            $table->integer('opening_balance')->default(0);
            $table->integer('closing_balance')->default(0);
            $table->integer('current_balance')->default(0);
            $table->integer('breakages')->default(0);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_balance');
    }
};
