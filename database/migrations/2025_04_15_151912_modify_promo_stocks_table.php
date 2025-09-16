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
        Schema::table('promo_stocks', function (Blueprint $table) {

            $table->enum('direction', ['in', 'out'])->default('in')->after('date');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_stocks', function (Blueprint $table) {
            // drop the direction column from the promo_stocks table
            $table->dropColumn('direction');
        });
    }
};
