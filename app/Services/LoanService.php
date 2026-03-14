<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Installment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanService
{
    /**
     * Create a new loan and generate its installment schedule automatically.
     *
     * @param array $data
     * @return Loan
     * @throws \Exception
     */
    public function createLoan(array $data): Loan
    {
        return DB::transaction(function () use ($data) {
            $loanAmount = (float) $data['loan_amount'];
            $productId = $data['loan_product_id'];
            $product = \App\Models\LoanProduct::findOrFail($productId);
            $interestRate = (float) $data['interest_rate'];

            // Assume start_date is the date the loan is disbursed
            $startDate = Carbon::parse($data['start_date']);

            // Calculate financial totals
            $interestAmount = $loanAmount * ($interestRate / 100);
            $totalPayable = $loanAmount + $interestAmount;

            // Calculate installments
            // Default to duration_weeks for now. In a real system, you might adjust based on frequency (monthly, fortnightly, etc.)
            $totalInstallments = $product->duration_weeks; 
            $installmentAmount = round($totalPayable / $totalInstallments, 2);

            // Generate unique loan application number (e.g LN-20231015-XYZ1)
            $loanNumber = 'LN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            // Create the main Loan record
            $loan = Loan::create([
                'loan_number' => $loanNumber,
                'member_id' => $data['member_id'],
                'loan_product_id' => $productId,
                'loan_amount' => $loanAmount,
                'interest_rate' => $interestRate,
                'total_payable' => $totalPayable,
                'installment_amount' => $installmentAmount,
                'total_installments' => $totalInstallments,
                'start_date' => $startDate->toDateString(),
                'status' => 'active', // Mark active upon generation
                'interest_rate_snapshot' => $product->interest_rate,
                'duration_weeks_snapshot' => $product->duration_weeks,
                'processing_fee_snapshot' => $product->processing_fee,
                'late_fee_snapshot' => $product->late_fee,
            ]);

            return $loan;
        });
    }
}
