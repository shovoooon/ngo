<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsAccount extends Model
{
    /** @use HasFactory<\Database\Factories\SavingsAccountFactory> */
    use HasFactory;

    protected $fillable = ['member_id', 'savings_product_id', 'balance'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function product()
    {
        return $this->belongsTo(SavingsProduct::class, 'savings_product_id');
    }

    public function transactions()
    {
        return $this->hasMany(SavingsTransaction::class, 'member_id', 'member_id');
    }
}
