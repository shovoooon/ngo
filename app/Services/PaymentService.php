<?php

namespace App\Services;

use App\Models\Installment;
use App\Models\Collection;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentService
{
    /**
     * Record a payment for a specific installment and update balances.
     *
     * @param Installment $installment
     * @param array $data
     * @return Collection
     * @throws Exception
     */
    public function recordPayment(Installment $installment, array $data): Collection
    {
        return DB::transaction(function () use ($installment, $data) {
            $paymentAmount = (float) $data['amount'];

            // Prevent overpayment of the installment
            if ($paymentAmount > $installment->amount) {
                throw new Exception("Payment amount exceeds the installment due amount.");
            }

            // Check if already paid
            if ($installment->status === 'paid') {
                throw new Exception("This installment is already marked as paid.");
            }

            $loan = $installment->loan;

            // Create the Collection record
            $collection = Collection::create([
                'member_id' => $loan->member_id,
                'loan_id' => $loan->id,
                'installment_id' => $installment->id,
                'amount' => $paymentAmount,
                'collected_by' => auth()->id(),
                'collected_at' => now(),
            ]);

            // Update Installment status
            // Note: For partial payments this logic would change, but per requirements we just prevent overpayment.
            // If the amount equals the due amount, mark it paid. 
            if ($paymentAmount >= $installment->amount) {
                $installment->update([
                    'status' => 'paid',
                    'paid_date' => now()->toDateString(),
                ]);
            }

            // Check if all installments are paid to close the loan
            $pendingInstallments = Installment::where('loan_id', $loan->id)
                ->where('status', '!=', 'paid')
                ->count();

            if ($pendingInstallments === 0) {
                $loan->update(['status' => 'completed']);
            }

            return $collection;
        });
    }
}
