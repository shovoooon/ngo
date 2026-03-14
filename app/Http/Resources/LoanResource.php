<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
            'loan_number' => $this->loan_number,
            'member_id' => $this->member_id,
            'loan_amount' => $this->loan_amount,
            'interest_rate' => $this->interest_rate,
            'total_payable' => $this->total_payable,
            'paid_amount' => $this->paid_amount,
            'remaining_balance' => $this->remaining_balance,
            'installment_amount' => $this->installment_amount,
            'total_installments' => $this->total_installments,
            'start_date' => $this->start_date,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'member' => new MemberResource($this->whenLoaded('member')),
            'collections' => CollectionResource::collection($this->whenLoaded('collections')),
        ];
    }
}
