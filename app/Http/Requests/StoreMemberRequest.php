<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:members,phone'],
            'nid' => ['nullable', 'string', 'max:50'],
            'village' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ];
    }
}
