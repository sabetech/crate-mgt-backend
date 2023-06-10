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
        Schema::create('empties_on_ground_products', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->foreignId('empties_on_ground_log_id')->constrained('empties_on_ground_log');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empties_on_ground_products');
    }
};
