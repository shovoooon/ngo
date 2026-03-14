<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'interest_rate',
        'interest_type',
        'duration_weeks',
        'installment_frequency',
        'processing_fee',
        'late_fee',
        'min_amount',
        'max_amount',
        'status',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
