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
        Schema::create('empties_transactions', function (Blueprint $table) {
            $table->id();
            $table->datetime('datetime');
            $table->string('transaction_id');
            $table->foreignId("product_id")->constrained("products");
            $table->integer('quantity');
            $table->enum('transaction_type',['in', 'out']);
            $table->enum('activity', ['customer_empties_return', 'ship_to_ggbl', 'receive_from_ggbl', 'customer_purchase', 'loadout_products', 'loadout_product_returns']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empties_transactions');
    }
};
