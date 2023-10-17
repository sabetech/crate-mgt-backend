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
        Schema::create('loadout', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('customer_id')->references('id')->on('customers');
            $table->foreignId('product_id')->references('id')->on('products');
            $table->integer('quantity');
            $table->integer('returned')->default(0);
            $table->integer('quantity_sold')->default(0);
            $table->integer('vse_outstandingbalance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loadout');
    }
};
