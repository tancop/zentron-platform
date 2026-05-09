<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('product'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'required|max:4095',
            'price' => 'required|numeric|min:0.01',
            'color' => 'nullable|string',
            'brand_id' => 'nullable|exists:Brand,id',
            'images' => 'nullable|array',
            'images.*' => [
                File::types(['jpg', 'jpeg', 'png', 'avif', 'webp'])
            ],
        ];
    }
}
