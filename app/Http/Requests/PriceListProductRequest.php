<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceListProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_uuid' => ['required', 'exists:products'],
            'price_list_uuid' => ['required', 'exists:price_lists'],
            'warehouse_uuid' => ['required', 'exists:warehouses'],
            'price' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
