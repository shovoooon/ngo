<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:members,id'],
            'loan_product_id' => ['required', 'exists:loan_products,id'],
            'loan_amount' => ['required', 'numeric', 'min:1000'],
            'interest_rate' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
        ];
    }
}
