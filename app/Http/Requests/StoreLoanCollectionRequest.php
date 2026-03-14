<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanCollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // If a user typed a loan_number (from the Quick Mobile Form), map it to the loan_id
        if ($this->has('loan_number') && !$this->has('loan_id')) {
            $loan = \App\Models\Loan::where('loan_number', $this->loan_number)->first();
            if ($loan) {
                $this->merge([
                    'loan_id' => $loan->id,
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'loan_id' => 'required|exists:loans,id',
            'loan_number' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'collection_date' => 'required|date|before_or_equal:today',
            'note' => 'nullable|string|max:1000',
        ];
    }
}
