<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\SavingsTransactionFactory> */
    use HasFactory;

    protected $fillable = ['member_id', 'type', 'amount', 'balance_after', 'note'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
