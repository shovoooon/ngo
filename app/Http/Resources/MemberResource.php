<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SavingsTransactionResource;

class MemberResource extends JsonResource
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
            'member_code' => $this->member_code,
            'name' => $this->name,
            'phone' => $this->phone,
            'nid' => $this->nid,
            'village' => $this->village,
            'address' => $this->address,
            'photo' => $this->photo,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'loans' => $this->whenLoaded('loans', function() {
                return LoanResource::collection($this->loans)->resolve();
            }),
            'savings_account' => $this->whenLoaded('savingsAccount', function() {
                return (new SavingsAccountResource($this->savingsAccount))->resolve();
            }),
            'savings_accounts' => $this->whenLoaded('savingsAccounts', function() {
                return SavingsAccountResource::collection($this->savingsAccounts)->resolve();
            }),
            'transactions' => $this->whenLoaded('transactions', function() {
                return SavingsTransactionResource::collection($this->transactions)->resolve();
            }),
        ];
    }
}
