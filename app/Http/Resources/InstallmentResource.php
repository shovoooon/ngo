<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstallmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'loan_id' => $this->loan_id,
            'installment_number' => $this->installment_number,
            'amount' => $this->amount,
            'due_date' => $this->due_date,
            'paid_date' => $this->paid_date,
            'status' => $this->status,
            'loan' => new LoanResource($this->whenLoaded('loan')),
        ];
    }
}
