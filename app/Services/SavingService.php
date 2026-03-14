<?php

namespace App\Services;

use App\Models\SavingsAccount;
use App\Models\SavingsTransaction;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Exception;

class SavingService
{
    /**
     * Process a savings transaction (deposit or withdrawal) ensuring atomicity
     * and preventing negative balances.
     *
     * @param Member $member
     * @param string $type
     * @param float $amount
     * @param string|null $note
     * @return SavingsTransaction
     * @throws Exception
     */
    public function processTransaction(Member $member, string $type, float $amount, ?string $note = null): SavingsTransaction
    {
        return DB::transaction(function () use ($member, $type, $amount, $note) {
            if ($amount <= 0) {
                throw new Exception("Transaction amount must be strictly greater than zero.");
            }

            // Retrieve or implicitly create the savings account, locking it for update.
            $account = SavingsAccount::firstOrCreate(
                ['member_id' => $member->id],
                ['balance' => 0]
            );

            // Re-fetch lock to prevent race conditions during heavy concurrent processing
            $account = SavingsAccount::where('id', $account->id)->lockForUpdate()->first();

            if ($type === 'withdraw') {
                if ($account->balance < $amount) {
                    throw new Exception("Insufficient funds. Withdrawal amount exceeds the available balance of $" . number_format($account->balance, 2) . ".");
                }
                $account->balance -= $amount;
            } elseif ($type === 'deposit') {
                $account->balance += $amount;
            } else {
                throw new Exception("Invalid transaction type specified.");
            }

            $account->save();

            // Record immutably into the ledger
            $transaction = SavingsTransaction::create([
                'member_id' => $member->id,
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $account->balance,
                'note' => $note,
            ]);

            return $transaction;
        });
    }
}
