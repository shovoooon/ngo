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
        Schema::table('loans', function (Blueprint $table) {
            $table->foreignId('loan_product_id')->nullable()->after('member_id')->constrained()->nullOnDelete();
            $table->decimal('interest_rate_snapshot', 8, 2)->nullable()->after('interest_rate');
            $table->integer('duration_weeks_snapshot')->nullable()->after('total_installments');
            $table->decimal('processing_fee_snapshot', 12, 2)->default(0)->after('duration_weeks_snapshot');
            $table->decimal('late_fee_snapshot', 12, 2)->default(0)->after('processing_fee_snapshot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['loan_product_id']);
            $table->dropColumn([
                'loan_product_id',
                'interest_rate_snapshot',
                'duration_weeks_snapshot',
                'processing_fee_snapshot',
                'late_fee_snapshot'
            ]);
        });
    }
};
