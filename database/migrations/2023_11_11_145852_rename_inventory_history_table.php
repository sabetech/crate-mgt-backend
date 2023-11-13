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
        Schema::rename('inventory_history', 'inventory_transactions');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::rename('inventory_transactions', 'inventory_history');
    }
};
