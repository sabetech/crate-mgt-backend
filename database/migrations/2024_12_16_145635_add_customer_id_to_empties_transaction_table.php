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
        Schema::table('empties_transactions', function (Blueprint $table) {
            //
            $table->foreignId("customer_id")->nullable()->constrained("customers")->after('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empties_transactions', function (Blueprint $table) {
            //
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');

        });
    }
};
