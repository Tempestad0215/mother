<?php

namespace App\Http\Requests;

use App\Enums\ProductTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
        $isArticle = $this->get('type') === 'producto';
        

        return [
            'name' => ['required','string','min:3','max:75'],
            'description' => ['nullable','string','max:150'],
            'unit_id' => ['nullable','integer','exists:units,id'],
            'supplier_id' => ['required','integer','exists:suppliers,id'],
            'category_id' => ['required','integer','exists:categories,id'],
            'warehouse_id' => ['required','integer','exists:warehouses,id'],
            'bar_code' => ['nullable','string','max:100'],
            'branch' => ['nullable','string','max:75','exists:branches,id'],
            'sku' => ['nullable','string','max:75'],
            'is_service' => [Rule::enum(ProductTypeEnum::class), 'required'],
            'tax_id' => ['required','numeric','exists:taxes,id'],
            'price' => ['required', 'numeric',Rule::notIn(0.00)],
            'min_price' => ['required', 'numeric',Rule::notIn(0.00)],
            'special_price' => ['required', 'numeric',Rule::notIn(0.00)],
            'cost' => ['required', 'numeric',Rule::notIn(0.00)],
            'benefits' => ['required', 'numeric',Rule::notIn(0.00)],
            'benefits_rate' => ['required', 'numeric',Rule::notIn(0.00)],
            'product_no_tax' => ['required','numeric',Rule::notIn(0.00)],
            'weight' => ['nullable','numeric'],
            'dimensions' => ['nullable','string','max:255'],
            'inventoried' => ['required','boolean'],
            'has_fraction' => ['required','boolean'],
            'status' => ['required','boolean'],
            'has_tax' => ['required','boolean'],
            'has_special' => ['required','boolean'],
            'has_promotion' => ['required','boolean'],
        ];
    }
}
