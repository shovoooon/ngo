<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    /** @use HasFactory<\Database\Factories\LoanFactory> */
    use HasFactory;

    protected $fillable = [
        'loan_number',
        'member_id',
        'loan_product_id',
        'loan_amount',
        'interest_rate',
        'total_payable',
        'installment_amount',
        'total_installments',
        'start_date',
        'status',
        'interest_rate_snapshot',
        'duration_weeks_snapshot',
        'processing_fee_snapshot',
        'late_fee_snapshot'
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function product()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    public function collections()
    {
        return $this->hasMany(LoanCollection::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->collections()->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_payable - $this->paid_amount;
    }
}
