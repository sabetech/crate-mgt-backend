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
        Schema::table('empties_on_ground_log', function (Blueprint $table) {

            $table->integer('number_of_pcs')->after('quantity')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empties_on_ground', function (Blueprint $table) {
            //
            $table->dropColumn('number_of_pcs');
        });
    }
};
