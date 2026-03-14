<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    /** @use HasFactory<\Database\Factories\InstallmentFactory> */
    use HasFactory;

    protected $fillable = ['loan_id', 'installment_number', 'amount', 'due_date', 'paid_date', 'status'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
