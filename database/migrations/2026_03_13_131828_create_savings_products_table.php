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
        Schema::create('savings_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('interest_rate', 8, 2);
            $table->string('interest_calculation'); // daily, monthly, etc.
            $table->decimal('minimum_balance', 12, 2)->default(0);
            $table->decimal('withdraw_limit', 12, 2)->default(0);
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_products');
    }
};
