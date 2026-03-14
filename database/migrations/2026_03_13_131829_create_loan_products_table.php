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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('interest_rate', 8, 2);
            $table->string('interest_type'); // flat, reducing, etc.
            $table->integer('duration_weeks');
            $table->string('installment_frequency'); // weekly, monthly, etc.
            $table->decimal('processing_fee', 12, 2)->default(0);
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->decimal('min_amount', 12, 2)->nullable();
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
