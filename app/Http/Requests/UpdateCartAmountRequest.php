<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartAmountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:Product,id',
            'amount' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'The amount must be at least 1.',
        ];
    }
}
