<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductSaleRequest extends FormRequest
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
            'client_name' => ['nullable', 'string','min:3','max:75'],
            'client_id' => ['nullable','integer'],
            'info' => ['required','array'],
            'info.*.name' => ['required','string','min:3','max:75'],
            'info.*.quantity' => ['required','numeric'],
            'info.*.price' => ['required','numeric'],
            'info.*.tax' => ['required','numeric'],
            'info.*.tax_rate' => ['required','numeric'],
            'info.*.amount' => ['required','numeric'],
            'tax' => ['required','numeric'],
            'amount' => ['required','numeric'],
            'sub_total' => ['required','numeric'],
            'total' => ['required','numeric'],
            'discount' => ['required','numeric'],
            'comment' => ['required','string','min:3','max:400'],
        ];
    }
}
