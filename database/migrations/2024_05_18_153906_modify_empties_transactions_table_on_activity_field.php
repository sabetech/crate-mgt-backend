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
        // Schema::table('empties_transactions', function (Blueprint $table) {
            // $table->enum('activity', ['customer_empties_return', 'ship_to_ggbl', 'customer_purchase'])->change();
            DB::statement("ALTER TABLE `empties_transactions` MODIFY `activity` ENUM('customer_empties_return', 'ship_to_ggbl', 'customer_purchase', 'loadout_products', 'loadout_product_returns', 'receive_from_ggbl') NOT NULL");
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
