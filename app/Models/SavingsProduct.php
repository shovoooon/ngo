<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'interest_rate',
        'interest_calculation',
        'minimum_balance',
        'withdraw_limit',
        'status',
    ];

    public function savingsAccounts()
    {
        return $this->hasMany(SavingsAccount::class);
    }
}
