<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_number')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->decimal('loan_amount', 10, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->decimal('total_payable', 10, 2);
            $table->decimal('installment_amount', 10, 2);
            $table->integer('total_installments');
            $table->date('start_date');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
