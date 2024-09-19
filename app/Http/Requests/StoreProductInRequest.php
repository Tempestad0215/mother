<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductInRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            'product_id' => ['required','exists:products,id'],
            'stock' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'tax' =>['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'discount' => ['nullable','numeric'],
            'discount_amount' => ['nullable','numeric'],
            'product_no_tax' => ['required','numeric'],
            'product_tax' => ['required','numeric'],
            'benefits' => ['required','numeric']
        ];
    }
}
