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
        Schema::create('customer_empties_account', function (Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id")->constrained("customers");
            $table->foreignId("product_id")->constrained("products");
            $table->integer("quantity_transacted");
            $table->date("date");
            $table->enum("transaction_type", ["in", "out"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_empties_account');
    }
};
