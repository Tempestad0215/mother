<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'supplier_uuid' => ['required','exists:suppliers,uuid', 'uuid'],
            'doc_date' => ['required','string'],
            'tax' => ['required','numeric','min:0'],
            'amount' => ['required','numeric','min:0'],
            'sub_total' => ['required','numeric','min:0'],
            'discount' => ['nullable','numeric','min:0'],
            'info' => ['required','array'],
            'info.*.uuid' => ['required','exists:products,uuid', 'uuid'],
            'info.*.code' => ['required', 'exists:products,code', 'string'],
            'info.*.quantity' => ['required','numeric','min:0'],
            'info.*.cost' => ['required','numeric','min:0'],
            'info.*.warehouse_uuid' => ['required','uuid','min:0','exists:warehouses,uuid'],
            'info.*.tax_uuid' => ['required','uuid','min:0','exists:taxes,uuid'],
            'info.*.tax' => ['required','numeric','min:0'],
            'info.*.discount_rate' => ['nullable','numeric','min:0'],
            'info.*.discount_amount' => ['nullable','numeric','min:0'],
            'info.*.amount' => ['nullable','numeric','min:0'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
