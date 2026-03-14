<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
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
            'amount' => $this->amount,
            'collected_by' => $this->collected_by,
            'collection_date' => $this->collection_date,
            'note' => $this->note,
            'collector' => [
                'id' => $this->collector?->id,
                'name' => $this->collector?->name,
            ],
            'member' => new MemberResource($this->whenLoaded('member')),
            'loan' => new LoanResource($this->whenLoaded('loan')),
        ];
    }
}
