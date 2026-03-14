<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory;

    protected $fillable = [
        'member_code',
        'name',
        'phone',
        'nid',
        'village',
        'address',
        'photo',
        'status'
    ];

    protected function casts(): array
    {
        return [
            //
        ];
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function savingsAccounts()
    {
        return $this->hasMany(SavingsAccount::class);
    }

    public function collections()
    {
        return $this->hasMany(LoanCollection::class);
    }

    public function transactions()
    {
        return $this->hasMany(SavingsTransaction::class);
    }
}
