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
        Schema::create('customer_loans', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('customer_id')->references('id')->on('customers');
            $table->integer('quantity_of_empties');
            $table->decimal('amount_paid');
            $table->date('date_due'); //usually next 2 weeks
            $table->string('loan_image_url');
            $table->enum('loan_status', ['pending', 'cleared', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_loans');
    }
};
