<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'uuid' => ['required'],
            'product_id' => ['required'],
            'warehouse_id' => ['required'],
            'stock_quantity' => ['required'],
            'min_stock' => ['required'],
            'max_stock' => ['required'],
            'is_active' => ['boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
