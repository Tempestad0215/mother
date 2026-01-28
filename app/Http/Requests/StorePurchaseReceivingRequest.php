<?php

namespace App\Http\Requests;

use App\Enums\PurchaseStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePurchaseReceivingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:purchases,id',
            'code' => 'nullable|string',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'user_id' => 'nullable|integer|exists:users,id',
            'supplier_name' => 'nullable|string',
            'doc_date' => 'required|date',
            'status' => ['required',Rule::enum(PurchaseStatusEnum::class),'string'],
            'amount' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'sub_total' => 'required|numeric|min:0',
            'comment' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|integer|exists:purchase_items,id',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.purchase_item_id' => 'nullable|integer|exists:purchase_items,id',
            'items.*.product_name' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.cost' => 'required|numeric|min:0',
            'items.*.tax_id' => 'required|numeric|exists:taxes,id',
            'items.*.tax_rate' => 'nullable|numeric|min:0',
            'items.*.tax_amount' => 'nullable|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.warehouse_id' => 'nullable|integer|exists:warehouses,id',
            'items.*.warehouse_name' => 'nullable|string',
            'items.*.isReadOnly' => 'nullable|boolean',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
