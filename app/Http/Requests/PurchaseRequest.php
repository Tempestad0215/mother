<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'supplier_id' => ['required','exists::suppliers.id', 'numeric'],
            'product_id' => ['required','exists::products.id', 'numeric'],
            'quantity' => ['required','numeric','min:0'],
            'price' => ['required','numeric','min:0'],
            'cost' => ['required','numeric','min:0'],
            'discount' => ['nullable','numeric','min:0'],
            'total_amount' => ['required','numeric','min:0'],
            'sub_total' => ['required','numeric','min:0'],
            'discount_global' => ['nullable','numeric','min:0'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
