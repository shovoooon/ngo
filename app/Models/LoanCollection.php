<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCollection extends Model
{
    protected $fillable = [
        'loan_id',
        'member_id',
        'amount',
        'collection_date',
        'collected_by',
        'note',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}
