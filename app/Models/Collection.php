<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    /** @use HasFactory<\Database\Factories\CollectionFactory> */
    use HasFactory;

    protected $fillable = ['member_id', 'loan_id', 'installment_id', 'amount', 'collected_by', 'collected_at'];

    protected function casts(): array
    {
        return [
            'collected_at' => 'datetime',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}
