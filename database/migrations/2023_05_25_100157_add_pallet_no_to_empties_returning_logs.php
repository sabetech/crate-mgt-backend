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
        Schema::table('empties_returning_logs', function (Blueprint $table) {
            //
            $table->unsignedInteger('number_of_pallets')->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empties_returning_logs', function (Blueprint $table) {
            //
            $table->dropColumn('number_of_pallets');
        });
    }
};
