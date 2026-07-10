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
            'uuid' => 'nullable|uuid|exists:purchases,uuid',
            'code' => 'nullable|string',
            'supplier_uuid' => 'required|uuid|exists:suppliers,uuid',
            'user_uuid' => 'nullable|uuid|exists:users,uuid',
            'supplier_name' => 'nullable|string',
            'doc_date' => 'required|date',
            'status' => ['required',Rule::enum(PurchaseStatusEnum::class),'string'],
            'amount' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'sub_total' => 'required|numeric|min:0',
            'comment' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.uuid' => 'nullable|uuid|exists:purchase_items,uuid',
            'items.*.product_uuid' => 'required|uuid|exists:products,uuid',
            'items.*.purchase_item_uuid' => 'nullable|uuid|exists:purchase_items,uuid',
            'items.*.product_name' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.cost' => 'required|numeric|min:0',
            'items.*.tax_uuid' => 'required|uuid|exists:taxes,uuid',
            'items.*.tax_rate' => 'nullable|numeric|min:0',
            'items.*.tax_amount' => 'nullable|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.warehouse_uuid' => 'nullable|uuid|exists:warehouses,uuid',
            'items.*.warehouse_name' => 'nullable|string',
            'items.*.isReadOnly' => 'nullable|boolean',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
