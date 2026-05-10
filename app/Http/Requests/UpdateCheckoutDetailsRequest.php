<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCheckoutDetailsRequest extends FormRequest
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
            'name' => 'required|string',
            'address-1' => 'required|string',
            'address-2' => 'nullable|string',
            'zip' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'email' => 'required|string',
            'phone-number' => 'nullable|string',
            'delivery-method' => 'required|integer|exists:DeliveryType,id',
            'terms' => 'accepted',
        ];
    }
}
