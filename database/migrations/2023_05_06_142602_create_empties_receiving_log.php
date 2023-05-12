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
        Schema::create('empties_receiving_log', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity_received');
            $table->string('vehicle_number');
            $table->string('purchase_order_number');
            $table->string('received_by');
            $table->string('delivered_by');
            $table->string('image_reference');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empties_receiving_log');
    }
};
