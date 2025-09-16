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
        /*
            product_sale_request (id and quantity)

            balance
        */
        Schema::create('inventory_history', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('product_id')->references('id')->on('products');
            $table->enum('activity', ['sale_request', 'sales_return', 'load_out', 'loadout_return_in', 'purchase_order', 'promo_stock_disbursement', 'other']);
            $table->string('comment')->nullable();
            $table->boolean('approved')->default(false);
            $table->integer('quantity');
            $table->integer('balance');
            $table->integer('approved_balance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_history');
    }
};
