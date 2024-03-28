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
            $table->string('way_bill_image_url')->after('purchase_order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_receivables', function (Blueprint $table) {
            //
            $table->dropColumn('way_bill_image_url');
        });
    }
};
